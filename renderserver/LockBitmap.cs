using System;
using System.Collections.Generic;
using System.Drawing;
using System.Drawing.Imaging;
using System.Linq;
using System.Runtime.InteropServices;
using System.Text;
using System.Threading.Tasks;

namespace RboxloRenderserver
{
    public class LockBitmap
    {
        IntPtr Iptr = IntPtr.Zero;
        BitmapData bitmapData = null;

        public Bitmap Source { get; set; }
        public byte[] Pixels { get; set; }
        public int Width { get; private set; }
        public int Height { get; private set; }

        public LockBitmap(Bitmap Source)
        {
            this.Source = Source;
        }

        public void LockBits()
        {
            try
            {
                Width = Source.Width;
                Height = Source.Height;
                int PixelCount = Width * Height;
                Rectangle rect = new Rectangle(0, 0, Width, Height);
                bitmapData = Source.LockBits(rect, ImageLockMode.ReadWrite, Source.PixelFormat);
                Pixels = new byte[PixelCount * 4];
                Iptr = bitmapData.Scan0;
                Marshal.Copy(Iptr, Pixels, 0, Pixels.Length);
            }
            catch (Exception ex)
            {
                throw ex;
            }
        }

        public void UnlockBits()
        {
            try
            {
                Marshal.Copy(Pixels, 0, Iptr, Pixels.Length);
                Source.UnlockBits(bitmapData);
            }
            catch (Exception ex)
            {
                throw ex;
            }
        }

        public Color GetPixel(int x, int y)
        {
            int i = ((y * Width) + x) * 4;

            if (i > Pixels.Length - 4)
            {
                throw new IndexOutOfRangeException();
            }

            byte b = Pixels[i];
            byte g = Pixels[i + 1];
            byte r = Pixels[i + 2];
            byte a = Pixels[i + 3];

            return Color.FromArgb(a, r, g, b);
        }

        public void SetPixel(int x, int y, Color color)
        {
            int i = ((y * Width) + x) * 4;

            Pixels[i] = color.B;
            Pixels[i + 1] = color.G;
            Pixels[i + 2] = color.R;
            Pixels[i + 3] = color.A;
        }
    }
}
