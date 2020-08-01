using System;
using System.Collections.Generic;
using System.Drawing;
using System.Windows;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Drawing.Imaging;
using System.Windows.Forms;
using System.IO;

namespace RboxloRenderserver
{
    public struct Coordinate
    {
        public int X, Y;
        public Coordinate(int x, int y)
        {
            this.X = x;
            this.Y = y;
        }
    }

    public class ScreenshotService
    {
        public static void TakeScreenshot(string filename, ImageFormat format)
        {
            Bitmap capture = new Bitmap((int)SystemParameters.PrimaryScreenWidth, (int)SystemParameters.PrimaryScreenHeight, PixelFormat.Format32bppArgb);
            Rectangle bounds = Screen.AllScreens[0].Bounds;
            Graphics gfx = Graphics.FromImage(capture);

            gfx.CopyFromScreen(bounds.Left, bounds.Top, 0, 0, bounds.Size);

            capture.Save(filename, format);
        }

        public static Bitmap Crop(Bitmap Image, Rectangle CropRectangle)
        {
            Bitmap target = new Bitmap(CropRectangle.Width, CropRectangle.Height);

            using (Graphics g = Graphics.FromImage(target))
            {
                g.DrawImage(Image, new Rectangle(0, 0, target.Width, target.Height),
                                 CropRectangle,
                                 GraphicsUnit.Pixel);
                return target;
            }
        }

        public List<Coordinate> GetMarkerCoordinates(LockBitmap Bitmap)
        {
            List<Coordinate> MarkerCoordinates = new List<Coordinate>();
            for (int y = 0; y < Bitmap.Height; y++)
            {
                for (int x = 0; x < Bitmap.Width; x++)
                {
                    Color color = Bitmap.GetPixel(x, y);
                    if (color == Color.FromArgb(0, 0, 255))
                    {
                        bool IsMarker = true;
                        for (int w = 0; w < 3; w++)
                        {
                            for (int v = 0; v < 3; v++)
                            {
                                IsMarker = IsMarker && (Bitmap.GetPixel(x + w, y + v) == ((w + v) % 2 == 0 ? Color.FromArgb(0, 0, 255) : Color.FromArgb(255, 0, 0)));
                            }
                        }
                        if (IsMarker)
                        {
                            MarkerCoordinates.Add(new Coordinate { X = x, Y = y });
                            x = x + 3;
                            y = y + 3;
                            Console.WriteLine(x + ", " + y);
                        }
                    }
                }
            }

            /*
                if (MarkerCoordinates.Count == 2)
                {
                    Bitmap.UnlockBits();
                    Bitmap.Source = Crop(
                        Bitmap.Source,
                        new Rectangle(
                            MarkerCoordinates[0].X,
                            MarkerCoordinates[0].Y,
                            (MarkerCoordinates[1].X - MarkerCoordinates[0].X) + 3,
                            (MarkerCoordinates[1].Y - MarkerCoordinates[0].Y) + 3
                        )
                    );

                    Bitmap.LockBits();
                }
            */

            return MarkerCoordinates;
        }
    }
}
