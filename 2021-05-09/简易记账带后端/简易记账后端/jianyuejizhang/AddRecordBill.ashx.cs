using jianyuejizhang.Bll;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace jianyuejizhang
{
    /// <summary>
    /// RecordBill 的摘要说明
    /// </summary>
    public class AddRecordBill : IHttpHandler
    {

        public void ProcessRequest(HttpContext context)
        {
            AddRecordBill_Bll.AddRecordBill(context);
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