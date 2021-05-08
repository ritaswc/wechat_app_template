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
    public class GetHistroyBillList_Bll
    {

        public  static void GetHistroyBillList(HttpContext context){
            context.Response.ContentType = "text/plain";
            GetHistroyBillList_Model model = new GetHistroyBillList_Model();
            Hashtable Param = new Hashtable();
            Param.Add("UserNo", URequest.RequestAll("UserNo"));
            Param.Add("StartDate", URequest.RequestAll("StartDate"));
            Param.Add("EndDate", URequest.RequestAll("EndDate"));
            Param.Add("PageSize", URequest.RequestAll("PageSize"));
            Param.Add("PageIndex", URequest.RequestAll("PageIndex"));

            DataSet ds = GetHistroyBillList_Dal.GetHistroyBillList(Param);

            foreach (DataTable dt in ds.Tables)
            {
                if (dt.TableName == "Table")
                {
                    model.Code = DataTableUtil.GetInt(dt, "Code");
                    model.Message = DataTableUtil.GetString(dt, "Message").Trim();
                   
                }else if(dt.TableName == "Table1"){
                    GetHistroyBillList_Model.Bill list = new  GetHistroyBillList_Model.Bill();
                    list.UserNo = DataTableUtil.GetString(dt, "UserNo").Trim();
                    list.UserName = DataTableUtil.GetString(dt, "UserName").Trim();
                    list.BillNo = DataTableUtil.GetString(dt, "BillNo");
                    list.BillMoney = DataTableUtil.GetDouble(dt, "Money");
                    list.BillPurpose = DataTableUtil.GetString(dt, "Purpose").Trim();
                    list.BillDate = DataTableUtil.GetString(dt, "BillDate").Trim();
                    list.BillRemark = DataTableUtil.GetString(dt, "Remark").Trim();
                    model.BillList.Add(list);
                  
                }

            }

            context.Response.Write(StringUtil.CJson(model));
            context.Response.End();
        }
             
    }
}