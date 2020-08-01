/*
 * RboxloRenderserver
 * by drslicendice, lightbulblighter
 *
 * Renders 2010-era thumbnails. Not designed for non-Windows systems.
 * Special thanks to drslicendice for the cropping programming, and most of the math here.
 * The RboxloRenderserver API is embedded into the Rboxlo framework.
 * 
 * RboxloRenderserver, as well as the other parts of the Rboxlo framework, can be found at https://www.github.com/lighterlightbulb/Rboxlo.
 * 
 * LockBitmap.cs        : BitMap utilities
 * ScreenshotService.cs : Windows screenshot helper
 * HttpApi.cs           : Communication with Rboxlo API
 * 
 * RboxloRenderserver v1.0.0
 * This is free and unencumbered software released into the public domain.
 * 
 * RboxloRenderserver < OPTIONS ... >
 *     -g, --game <game_executable> The path to the game/render executable. Defaults to "{CURRENT_DIR}/client/"
 *     -p, --path <path>            The working path of the RenderServer for thumbnails. Defaults to "{CURRENT_DIR}/thumbnails/"
 *     -d, --debug                  Debugging mode flag. Optional
 *     -h, --help                   Displays this help message
 *     -a, --authorization <key>    [ REQUIRED ] Authorization key.
 *     -w, --website <domain>       [ REQUIRED ] Website / domain to upload thumbnails to, ex: https://rboxlo.xyz/
 */

using System;
using System.Collections.Generic;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace RboxloRenderserver
{
    class Program
    {
        private static readonly ScreenshotService Service = new ScreenshotService();

        private static int CalculateAlpha(int ColorChannel, int Alpha)
        {
            if (Alpha == 0 || Alpha == 255)
            {
                return ColorChannel;
            }
            else
            {
                double Value = (ColorChannel - 255.0 + 255.0 * (Alpha / 255.0)) / (Alpha / 255.0);
                return (Value < 0) ? 0 : (Value > 255) ? 255 : (int)Value;
            }
        }
        
        private static LockBitmap ProcessScreenshot(string alphaPath, string colorPath)
        {
            LockBitmap AlphaScreenshot = new LockBitmap((Bitmap)Image.FromFile(alphaPath));
            LockBitmap ColorScreenshot = new LockBitmap((Bitmap)Image.FromFile(colorPath));
            AlphaScreenshot.LockBits();
            ColorScreenshot.LockBits();

            List<Coordinate> MarkerCoordinates = Service.GetMarkerCoordinates(AlphaScreenshot);

            for (int y = MarkerCoordinates[0].Y; y < (MarkerCoordinates[1].Y + 3); y++)
            {
                for (int x = MarkerCoordinates[0].X; x < (MarkerCoordinates[1].X + 3); x++)
                {
                    Color AlphaColor = AlphaScreenshot.GetPixel(x, y);
                    Color MainColor = ColorScreenshot.GetPixel(x, y);

                    byte Alpha = (AlphaColor.G - 2 > AlphaColor.R && AlphaColor.G - 2 > AlphaColor.B) ? (byte)(255 - AlphaColor.G) : (byte)255;
                    ColorScreenshot.SetPixel(x, y, Color.FromArgb(
                        CalculateAlpha(MainColor.R, Alpha),
                        CalculateAlpha(MainColor.G, Alpha),
                        CalculateAlpha(MainColor.B, Alpha)
                    ));
                }
            }

            AlphaScreenshot.UnlockBits();
            ColorScreenshot.UnlockBits();

            return ColorScreenshot;
        }

        private static void DisplayHelpMessage()
        {
            Console.WriteLine("RboxloRenderserver v1.0.0");
            Console.WriteLine("This is free and unencumbered software released into the public domain.\n");
            
            Console.WriteLine("RboxloRenderserver < OPTIONS ... >");
            Console.WriteLine("\t-g, --game <game_executable> The path to the game/render executable. Defaults to \"{CURRENT_DIR}/client/\"");
            Console.WriteLine("\t-p, --path <path>            The working path of the RenderServer for thumbnails. Defaults to \"{CURRENT_DIR}/thumbnails/\"");
            Console.WriteLine("\t-d, --debug                  Debugging mode flag. Optional");
            Console.WriteLine("\t-h, --help                   Displays this help message");
            Console.WriteLine("\t-a, --authorization <key>    [ REQUIRED ] Authorization key.");
            Console.WriteLine("\t-w, --website <domain>       [ REQUIRED ] Website / domain to upload thumbnails to, ex: https://rboxlo.xyz/");

            Console.WriteLine("\nPress any key to continue . . .");
            Console.ReadKey();
            Environment.Exit(0);
        }

        private static void Main(string[] args)
        {
            DisplayHelpMessage();
        }
    }
}
