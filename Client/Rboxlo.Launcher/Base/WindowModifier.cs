using System;
using System.Runtime.InteropServices;
using System.Windows;
using System.Windows.Interop;

namespace Rboxlo.Launcher.Base
{
    /// <summary>
    /// Modifies Windows windows with Win32. Say that three times fast
    /// </summary>
    public static class WindowModifier
    {
        [DllImport("user32.dll")]
        private static extern int GetWindowLong(IntPtr hwnd, int index);

        [DllImport("user32.dll")]
        private static extern int SetWindowLong(IntPtr hwnd, int index, int newStyle);

        private const int GWL_STYLE = -16;
        private const int GWL_EXSTYLE = -20;
        private const int WS_EX_DLGMODALFRAME = 0x0001;
        private const int WS_SYSMENU = 0x80000;

        /// <summary>
        /// Removes the icon from a WPF window
        /// </summary>
        /// <param name="window">Window to modify</param>
        public static void RemoveIcon(Window window)
        {
            IntPtr hWnd = new WindowInteropHelper(window).Handle;
            int extendedStyle = GetWindowLong(hWnd, GWL_EXSTYLE);

            SetWindowLong(hWnd, GWL_EXSTYLE, extendedStyle | WS_EX_DLGMODALFRAME);
        }

        /// <summary>
        /// Removes the close button from a WPF window
        /// </summary>
        /// <param name="window">Window to modify</param>
        public static void RemoveCloseButton(Window window)
        {
            IntPtr hWnd = new WindowInteropHelper(window).Handle;
            int style = GetWindowLong(hWnd, GWL_STYLE);

            SetWindowLong(hWnd, GWL_STYLE, style & ~WS_SYSMENU);
        }
    }
}
