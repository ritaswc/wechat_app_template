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
    public class GetHistroyBillList_Dal
    {
        public static DataSet GetHistroyBillList(Hashtable Param)
        {

            List<SqlParameter> liParam = new List<SqlParameter>();
            liParam.Add(new SqlParameter("@UserNo", Param["UserNo"]));              //用户唯一码
            liParam.Add(new SqlParameter("@StartDate", Param["StartDate"]));        //登录密匙
            liParam.Add(new SqlParameter("@EndDate", Param["EndDate"]));                //公司唯一码
            liParam.Add(new SqlParameter("@PageSize", Param["PageSize"]));          //每页加载多少条数据
            liParam.Add(new SqlParameter("@PageIndex", Param["PageIndex"]));        //第几页

            DBHelper db = new DBHelper(ConfigurationManager.ConnectionStrings["SQLServerConnectionString"].ConnectionString);

            return db.ExecuteProcedureTrans("Proc_GetHistroyBillList", liParam.ToArray()); //调用存储过程，返回数据
       
        }
    }
}