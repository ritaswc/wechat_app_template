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
    public class AddRecordBill_Dal
    {
        public static DataSet AddRecordBill(Hashtable Param)
        {

            List<SqlParameter> liParam = new List<SqlParameter>();
            liParam.Add(new SqlParameter("@UserNo", Param["UserNo"]));              //用户唯一码
            liParam.Add(new SqlParameter("@Date", Param["Date"]));        
            liParam.Add(new SqlParameter("@Purpose", Param["Purpose"]));              
            liParam.Add(new SqlParameter("@Money", Param["Money"]));          
            liParam.Add(new SqlParameter("@Remark", Param["Remark"]));
            liParam.Add(new SqlParameter("@BillNo", Param["BillNo"]));   
            DBHelper db = new DBHelper(ConfigurationManager.ConnectionStrings["SQLServerConnectionString"].ConnectionString);

            return db.ExecuteProcedureTrans("Proc_AddRecordBill", liParam.ToArray()); //调用存储过程，返回数据
       
        }
    }
}