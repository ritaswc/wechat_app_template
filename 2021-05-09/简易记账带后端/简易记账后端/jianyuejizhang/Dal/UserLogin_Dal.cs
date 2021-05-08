using System;
using System.Collections;
using System.Collections.Generic;
using System.Configuration;
using System.Data;
using System.Data.SqlClient;
using System.Linq;
using System.Web;
using Util;
using Utils;

namespace jianyuejizhang.Dal
{
    public class UserLogin_Dal
    {
        public static DataSet UserLogin(Hashtable Param)
        {

            List<SqlParameter> liParam = new List<SqlParameter>();
            liParam.Add(new SqlParameter("@UserNo", Param["UserNo"]));              //用户唯一码
            liParam.Add(new SqlParameter("@UserImg", Param["UserImg"]));
            liParam.Add(new SqlParameter("@UserName", Param["UserName"]));              
          
            DBHelper db = new DBHelper(ConfigurationManager.ConnectionStrings["SQLServerConnectionString"].ConnectionString);

            return db.ExecuteProcedureTrans("Proc_UserLogin", liParam.ToArray()); //调用存储过程，返回数据
       
        }
    }
}