﻿using System;
using System.Linq;
using System.IO;
using System.Collections.Generic;
using System.Reflection;
using System.Text;

namespace Rboxlo.DotEnv
{
    class Program
    {
        /// <summary>
        /// Friendly name for assembly
        /// </summary>
        private static readonly string ApplicationName = Assembly.GetExecutingAssembly().GetName().Name;

        /// <summary>
        /// A constant
        /// </summary>
        public struct Constant
        {
            public string Type;
            public string Variable;
            public string Data;

            public Constant(string type, string variable, string data)
            {
                Type = type;
                Variable = variable;
                Data = data;
            }
        }

        /// <summary>
        /// Shows "Press any key to continue" and optionally an error message
        /// </summary>
        /// <param name="message">Optional error message</param>
        private static void Fail(string message = null)
        {
            if (message != null)
            {
                Console.WriteLine(message);
            }

            Console.WriteLine($"{Environment.NewLine}Press any key to continue . . .");
            Console.ReadKey();

            Environment.Exit(1);
        }

        /// <summary>
        /// Application entrypoint for Rboxlo.DotEnv
        /// </summary>
        /// <param name="args">Command line arguments</param>
        static void Main(string[] args)
        {
            Console.Title = ApplicationName;

            if (args.Length < 3)
            {
                Console.WriteLine($"You need at least three arguments to use {ApplicationName}.");
                Console.WriteLine("The first argument is the dotenv file to parse, the second is the output file path, and the third (and the ones after) are the constants to import.");
                Console.WriteLine(@$"For example, '{ApplicationName}.exe .env C:\Client\Core\GeneratedConstants.cs PROJECT_NAME WEBSITE_DOMAIN'");

                Fail();
            }

            // Step 1: Parse dotenv

            string dotenv = args[0];
            string output = args[1];
            string[] constants = (args.Skip(2)).ToArray(); // Skip dotenv and output arguments
            
            if (!File.Exists(dotenv))
            {
                Fail("Provided dotenv file does not exist");
            }
            
            List<Constant> parsed = new List<Constant>();

            foreach (string line in File.ReadLines(dotenv))
            {
                if (
                    (line != String.Empty || line != Environment.NewLine) && /* Skip newlines, or empty lines */
                    (line.FirstOrDefault() != '#') && /* Skip comments */
                    (line.Contains("=")) /* Sanity check */
                   )
                {
                    string[] values = line.Split('=');
                    values[0] = values[0].Trim();
                    values[1] = values[1].Trim();

                    if (Array.IndexOf(constants, values[0]) >= 0)
                    {
                        // 0 : Variable name
                        // 1 : Variable raw data
                        string type;
                        string variable = values[0];
                        string data = values[1];

                        // Figure out type
                        if (data.ToLower() == "false" || data.ToLower() == "true")
                        {
                            type = "bool";
                            data = data.ToLower(); // "FALSE", "TRUE" are invalid
                        }
                        else if (int.TryParse(data, out int _))
                        {
                            type = "int";
                        }
                        else
                        {
                            type = "string";
                            data = $"\"{data}\""; // Strings need to be wrapped in quotes
                        }

                        parsed.Add(new Constant(type, variable, data));
                    }
                }
            }

            // Step 2: Generate output

            // Template modifiers
            string nl = Environment.NewLine;
            string tb = "    ";
            string classname = (output.Split('.')[1]).Split('\\').Last();

            // Looks ugly, but meh... it works
            Console.WriteLine($"{ApplicationName} -> Generating file...");
            StringBuilder buffer = new StringBuilder();

            buffer.Append($"// This file was automatically generated by {ApplicationName}{nl}{nl}");

            buffer.Append($"using System;{nl}{nl}");

            buffer.Append($"namespace Rboxlo.Core{nl}"); // TODO: Dynamic namespace?
            buffer.Append($"{{{nl}");
                buffer.Append($"{tb}public static class {classname}{nl}");
                buffer.Append($"{tb}{{{nl}");
                    foreach (Constant constant in parsed)
                    {
                        buffer.Append($"{tb}{tb}public static {constant.Type} {constant.Variable} = {constant.Data};{nl}");
                    }
                buffer.Append($"{tb}}}{nl}");
            buffer.Append($"}}{tb}");

            // Save generated output
            File.WriteAllText(output, buffer.ToString());
            Console.WriteLine($"{ApplicationName} -> Successfully generated file!");

            // Step 3: Exit peacefully so Visual Studio doesn't freak out
            
            Environment.Exit(0);
        }
    }
}
