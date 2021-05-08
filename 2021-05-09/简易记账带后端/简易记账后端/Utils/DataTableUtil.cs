using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Web;

namespace Util
{
    public class DataTableUtil
    {
        /// <summary>
        /// 返回DataTable第一行指定列名的数据
        /// </summary>
        /// <param name="dt">数据表</param>
        /// <param name="comlums">列名</param>
        /// <returns></returns>
        public static int GetInt(DataTable dt, string comlums)
        {
            object obj = 0;

            if (dt.Rows.Count > 0)
            {
                obj = dt.Rows[0][comlums] == DBNull.Value ? 0 : dt.Rows[0][comlums];
            }

            return Convert.ToInt32(obj);
        }

        /// <summary>
        /// 获取首行首列的int值
        /// </summary>
        /// <param name="dt">数据表</param>
        /// <returns></returns>
        public static int GetScalarInt(DataTable dt)
        {
            object obj = 0;

            if (dt.Rows.Count > 0)
            {
                obj = dt.Rows[0][0] == DBNull.Value ? 0 : dt.Rows[0][0];
            }

            return Convert.ToInt32(obj);
        }

        /// <summary>
        /// 获取首行首列的string值
        /// </summary>
        /// <param name="dt">数据表</param>
        /// <returns></returns>
        public static string GetScalarString(DataTable dt)
        {
            object obj = 0;

            if (dt.Rows.Count > 0)
            {
                obj = dt.Rows[0][0] == DBNull.Value ? 0 : dt.Rows[0][0];
            }

            return Convert.ToString(obj);
        }

        /// <summary>
        /// 返回DataTable第一行指定列名的数据
        /// </summary>
        /// <param name="dt">数据表</param>
        /// <param name="comlums">列名</param>
        /// <returns></returns>
        public static String GetString(DataTable dt, string comlums)
        {
            object obj = "";

            if (dt.Rows.Count > 0)
            {
                obj = dt.Rows[0][comlums] == DBNull.Value ? "" : dt.Rows[0][comlums];
            }

            return Convert.ToString(obj);
        }

        /// <summary>
        /// 返回DataRow指定列名的的数据
        /// </summary>
        /// <param name="dr">行数据</param>
        /// <param name="comlums">列名</param>
        /// <returns></returns>
        public static int GetInt(DataRow dt, string comlums)
        {
            object obj = null;
            if (dt.Table.Columns.Contains(comlums))
            {
                obj = dt[comlums] == DBNull.Value ? 0 : dt[comlums];
            }
            else
                return 0;
            return Convert.ToInt32(obj);
        }

        /// <summary>
        /// 返回DataRow指定列名的的数据
        /// </summary>
        /// <param name="dr">行数据</param>
        /// <param name="comlums">列名</param>
        /// <returns></returns>
        public static long Getlong(DataRow dt, string comlums)
        {
            object obj = null;
            if (dt.Table.Columns.Contains(comlums))
            {
                obj = dt[comlums] == DBNull.Value ? 0 : dt[comlums];
            }
            else
                return 0;
            return Convert.ToInt64(obj);
        }

        /// <summary>
        /// 返回DataRow指定列名的的数据
        /// </summary>
        /// <param name="dt">行数据</param>
        /// <param name="comlums">列名</param>
        /// <returns></returns>
        public static String GetString(DataRow dt, string comlums)
        {
            object obj = null;
            if (dt.Table.Columns.Contains(comlums))
            {
                obj = dt[comlums] == DBNull.Value ? "" : dt[comlums];
                return Convert.ToString(obj);
            }
            else
                return "";
        }

        /// <summary>
        /// 返回DataTable第一行指定列名的数据
        /// </summary>
        /// <param name="dt">数据表</param>
        /// <param name="comlums">列名</param>
        /// <returns></returns>
        public static Double GetDouble(DataTable dt, string comlums)
        {
            object obj = "";

            if (dt.Rows.Count > 0)
            {
                obj = dt.Rows[0][comlums] == DBNull.Value ? 0.0 : dt.Rows[0][comlums];
            }

            return Convert.ToDouble(obj);
        }


        /// <summary>
        /// 返回DataRow指定列名的的数据
        /// </summary>
        /// <param name="dt">行数据</param>
        /// <param name="comlums">列名</param>
        /// <returns></returns>
        public static Double GetDouble(DataRow dt, string comlums)
        {
            object obj = null;
            if (dt.Table.Columns.Contains(comlums))
            {
                obj = dt[comlums] == DBNull.Value ? "" : dt[comlums];
                return Convert.ToDouble(obj);
            }
            else
                return 0.0;
        }

    }
}