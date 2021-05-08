using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Utils
{
    public class URequest
    {

        /// <summary>
        /// 通配Get或者Post值
        /// </summary>
        /// <param name="str"></param>
        /// <returns></returns>
        public static string RequestAll(string str)
        {
            string requestValue = RequestForm(str);
            if (requestValue == string.Empty)
            {
                requestValue = RequestQuery(str);
                if (requestValue == string.Empty)
                {
                    requestValue = "";
                }
            }
            return requestValue;
        }

        /// <summary>
        /// 获取Get参数值
        /// </summary>
        /// <param name="str"></param>
        /// <returns></returns>
        public static string RequestQuery(string str)
        {
            if (System.Web.HttpContext.Current.Request.QueryString[str] != null)
            {
                return System.Web.HttpContext.Current.Request.QueryString[str].ToString();
            }
            else
            {
                return "";
            }
        }


        /// <summary>
        /// 获取Post传参
        /// </summary>
        /// <param name="str"></param>
        /// <returns></returns>
        public static string RequestForm(string str)
        {
            if (System.Web.HttpContext.Current.Request.Form[str] != null)
            {
                return System.Web.HttpContext.Current.Request.Form[str].ToString().Trim(' ');
            }
            else
            {
                return "";
            }
        }

    }
}
