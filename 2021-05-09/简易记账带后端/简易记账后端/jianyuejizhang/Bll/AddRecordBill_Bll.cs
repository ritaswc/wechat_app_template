using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Collections;
using Utils;
using jianyuejizhang.Model;
using Util;
using System.Data;
using jianyuejizhang.Dal;

namespace jianyuejizhang.Bll
{
    public class AddRecordBill_Bll
    {

        public static void AddRecordBill(HttpContext context)
        {
            context.Response.ContentType = "text/plain";
            AddRecordBill_Model model = new AddRecordBill_Model();
            Hashtable Param = new Hashtable();
            Param.Add("UserNo", URequest.RequestAll("UserNo"));
            Param.Add("Date", URequest.RequestAll("Date"));
            Param.Add("Purpose", URequest.RequestAll("Purpose"));
            Param.Add("Money", URequest.RequestAll("Money"));
            Param.Add("Remark", URequest.RequestAll("Remark"));
         
            string BillNo = Guid.NewGuid().ToString("N");

            Param.Add("BillNo", BillNo);

            DataSet ds = AddRecordBill_Dal.AddRecordBill(Param);

            foreach (DataTable dt in ds.Tables)
            {
                if (dt.TableName == "Table")
                {
                    model.Code = DataTableUtil.GetInt(dt, "Code");
                    model.Message = DataTableUtil.GetString(dt, "Message").Trim();
                   
                }

            }

            context.Response.Write(StringUtil.CJson(model));
            context.Response.End();
        }
             
    }
}