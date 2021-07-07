using System;
using System.Windows.Forms;
using Rboxlo.Core;

namespace Rboxlo.Launcher.Base
{
    /// <summary>
    /// Class for the little balloon icon in your taskbar
    /// </summary>
    public class TrayIcon
    {
        private NotifyIcon trayIcon;
        private ContextMenu menu;

        /// <summary>
        /// Class constructor for TrayIcon
        /// </summary>
        /// <param name="_menu">Initial ContextMenu</param>
        public TrayIcon(ContextMenu _menu)
        {
            menu = _menu;

            trayIcon = new NotifyIcon()
            {
                Icon = Properties.Resources.AppIcon,
                Text = Constants.ProjectName,
                ContextMenu = _menu
            };
        }

        /// <summary>
        /// Sets visibility of trayIcon to true
        /// </summary>
        public void Display()
        {
            trayIcon.Visible = true;
        }

        /// <summary>
        /// Sets visibility of trayIcon to false
        /// </summary>
        public void Hide()
        {
            trayIcon.Visible = false;
        }

        /// <summary>
        /// Fully destroys TrayIcon. You cannot use TrayIcon after this
        /// </summary>
        public void Destroy()
        {
            trayIcon.Visible = false;
            trayIcon.Icon = null;
        }

        /// <summary>
        /// Gets current ContextMenu
        /// </summary>
        /// <returns>Current ContextMenu</returns>
        public ContextMenu GetMenu()
        {
            return menu;
        }

        /// <summary>
        /// Sets ContextMenu
        /// </summary>
        /// <param name="_menu">New ContextMenu</param>
        public void SetMenu(ContextMenu _menu)
        {
            menu = _menu;
            trayIcon.ContextMenu = _menu;
        }
    }
}
