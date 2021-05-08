using jianyuejizhang.Bll;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;


namespace jianyuejizhang
{
    /// <summary>
    /// GetHistroyBillList 的摘要说明
    /// </summary>
    public class GetHistroyBillList : IHttpHandler
    {

        public void ProcessRequest(HttpContext context)
        {
           
            GetHistroyBillList_Bll.GetHistroyBillList(context);
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