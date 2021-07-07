using System;
using Newtonsoft.Json.Linq;

namespace Rboxlo.Core.Common
{
    /// <summary>
    /// Response from LicenseValidator.Check
    /// </summary>
    public struct LicenseResponse
    {
        public bool Success;
        public string LACIWID;

        public LicenseResponse(bool success, string laciwid)
        {
            this.Success = success;
            this.LACIWID = laciwid;
        }
    }

    public class LicenseValidator : IDisposable
    {
        private static string application;
        private static string userAgent;
        private static InternetConnection connection;

        private static bool disposedValue;

        /// <summary>
        /// Class constructor for LicenseValidator
        /// </summary>
        /// <param name="_application">Name of application (e.g. "launcher")</param>
        /// <param name="_userAgent">User agent string (e.g. "Rboxlo/Launcher 1.0")</param>
        /// <param name="_connection">Current InternetConnection</param>
        public LicenseValidator(string _application, string _userAgent, InternetConnection _connection)
        {
            application = _application;
            userAgent = _userAgent;
            connection = _connection;
        }

        /// <summary>
        /// Sees if license check is required for this application
        /// </summary>
        /// <returns>Is required or not</returns>
        public bool Required()
        {
            InternetResponse response = connection.Get(String.Format("{0}/api/license/check&application={1}", Constants.BaseURL, application), userAgent: userAgent);
            JObject body = JObject.Parse(response.Data);

            return body["objects"]["required"].ToObject<bool>();
        }

        /// <summary>
        /// Checks a license
        /// </summary>
        /// <param name="base64">Base64 license</param>
        /// <returns>LicenseOperation</returns>
        public LicenseResponse Check(string base64)
        {
            InternetResponse response = connection.Post(url: String.Format("{0}/api/license/set", Constants.BaseURL), isForm: false, body: base64, userAgent: userAgent);
            JObject body = JObject.Parse(response.Data);

            bool success = body["success"].ToObject<bool>();
            string laciwid = null;

            if (success)
            {
                laciwid = body["objects"]["laciwid"].ToString();
            }

            return new LicenseResponse { Success = success, LACIWID = laciwid };
        }

        /// <summary>
        /// Disposes of LicenseChecker
        /// </summary>
        protected virtual void Dispose(bool disposing)
        {
            if (!disposedValue)
            {
                application = null;
                userAgent = null;
                disposedValue = true;
            }
        }

        public void Dispose()
        {
            // Do not change this code. Put cleanup code in 'Dispose(bool disposing)' method
            Dispose(disposing: true);
            GC.SuppressFinalize(this);
        }
    }
}
