using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Runtime.Serialization.Json;
using System.Text;
using System.Threading.Tasks;

namespace Util
{
   public class StringUtil
    {
        // 从一个对象信息生成Json串  
        public static string CJson(object model)
        {

            DataContractJsonSerializer serializer = new DataContractJsonSerializer(model.GetType());
            MemoryStream stream = new MemoryStream();
            serializer.WriteObject(stream, model);
            byte[] dataBytes = new byte[stream.Length];
            stream.Position = 0;
            stream.Read(dataBytes, 0, (int)stream.Length);
            return Encoding.UTF8.GetString(dataBytes);
        }

        // 从一个Json串生成对象信息  
        public static object JsonToObject(string jsonString, object obj)
        {
            DataContractJsonSerializer serializer = new DataContractJsonSerializer(obj.GetType());
            MemoryStream mStream = new MemoryStream(Encoding.UTF8.GetBytes(jsonString));
            return serializer.ReadObject(mStream);
        }
    }
}
