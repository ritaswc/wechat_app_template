using jianyuejizhang.Bll;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace jianyuejizhang
{
    /// <summary>
    /// UserLogin 的摘要说明
    /// </summary>
    public class UserLogin : IHttpHandler
    {

        public void ProcessRequest(HttpContext context)
        {
            UserLogin_Bll.UserLogin(context);
        }

        public bool IsReusable
        {
            get
            {
                return false;
            }
        }
    }
}