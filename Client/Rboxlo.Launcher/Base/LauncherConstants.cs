using System;
using System.IO;
using Rboxlo.Core;
using Rboxlo.Core.Common;

namespace Rboxlo.Launcher.Base
{
    /// <summary>
    /// Launcher-specific constants
    /// </summary>
    public static class LauncherConstants
    {
        public static readonly string LocalApplicationData = Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData);
        public static readonly string CurrentWorkingDirectory = Directory.GetCurrentDirectory();
        public static readonly string ApplicationFolder = Path.Combine(LocalApplicationData, Constants.ProjectName);
        public static readonly string ApplicationName = Util.ToMachineReadable(Constants.ProjectName.ToLower());
        public static readonly string CurrentApp = "launcher";
        public static readonly string UserAgent = String.Format("{0}/1.0 ({1})", Constants.ProjectName, Util.ToTitleCase(CurrentApp)); // x/1.0 (Launcher)
    }
}
