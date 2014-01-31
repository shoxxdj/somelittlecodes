#Give in hexa the color of a specified pixel
#Just need PIL 
#ShoxX CopyLeft : www.shoxx-website.com # 

from PIL import Image

def rgb2hex(r, g, b):
    return '{:02x}{:02x}{:02x}'.format(r, g, b)

im = Image.open('color.png')
rgb_im = im.convert('RGB')
r, g, b = rgb_im.getpixel((1, 1))
rgb=rgb2hex(r,g,b)
