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
    public class UserLogin_Bll
    {

        public static void UserLogin(HttpContext context)
        {
            context.Response.ContentType = "text/plain";
            UserLogin_Model model = new UserLogin_Model();
            Hashtable Param = new Hashtable();
            Param.Add("UserNo", URequest.RequestAll("UserNo"));
            Param.Add("UserImg", URequest.RequestAll("UserImg"));
            Param.Add("UserName", URequest.RequestAll("UserName"));


            DataSet ds = UserLogin_Dal.UserLogin(Param);

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