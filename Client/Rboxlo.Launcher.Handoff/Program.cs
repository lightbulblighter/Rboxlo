using System;
using System.IO;
using System.Diagnostics;

namespace Rboxlo.Launcher.Handoff
{
    class Program
    {
        static readonly string launcher = Path.Combine(Directory.GetCurrentDirectory(), "Rboxlo.Launcher.exe");

        static void Main(string[] args)
        {
            if (!File.Exists(launcher) || args.Length == 0)
            {
                Environment.Exit(0);
            }

            if (args[0] == "--uninstall")
            {
                File.Delete(launcher);
            }

            if (args[0] == "--args")
            {
                if (args.Length == 1)
                {
                    Environment.Exit(0);
                }

                string largs = args[1]; // Launcher ARGS
                largs = largs.Substring(1, largs.Length - 2);

                // launch
                ProcessStartInfo handoff = new ProcessStartInfo(launcher)
                {
                    Arguments = largs,
                    UseShellExecute = true
                };

                Process.Start(handoff);

                // bye bye baby
                Environment.Exit(0);
            }
        }
    }
}
