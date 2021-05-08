using System;
using System.Configuration;
using System.Data;
using System.Data.SqlClient;

namespace Util
{
    public class DataBaseHelper
    {
        #region 属性

        /// <summary>
        /// 连接字符串
        /// </summary>
        public string connectString { get; set; }

        /// <summary>
        /// 连接数据库对象
        /// </summary>
        private SqlConnection connection;

        /// <summary>
        /// 控制数据访问对象
        /// </summary>
        private SqlCommand command;

        /// <summary>
        /// 控制适配器
        /// </summary>
        private SqlDataAdapter dataAdapter;

        #endregion 属性

        #region 构造方法

        public DataBaseHelper()
        {
            //获取连接字符串。
            this.connectString = ConfigurationManager.ConnectionStrings["SQLServerConnectionString"].ConnectionString;

        }

        #endregion 构造方法

        #region 方法

        #region 打开连接

        private void Open()
        {
            this.connection = new SqlConnection(this.connectString);
            if (ConnectionState.Open != this.connection.State)
            {
                this.connection.Open();
            }
        }

        #endregion 打开连接

        #region 关闭连接

        private void Close()
        {
            if (ConnectionState.Closed != this.connection.State)
            {
                this.connection.Close();
                this.connection.Dispose();
            }
        }

        #endregion 关闭连接

        #region 查询多行数据

        /// <summary>
        /// 执行筛选操作
        /// </summary>
        /// <param name="sql">筛选的SQL语句</param>
        /// <param name="param">语句中所相关的参数</param>
        /// <returns>DataTable类型数据集</returns>
        public DataTable Select(string sql, SqlParameter[] param)
        {
            //实例化一个控制访问对象，和连接对象，并初始化连接字符串
            this.command = new SqlCommand();
            this.connection = new SqlConnection(this.connectString);
            this.command.Connection = this.connection;

            //如果参数不为空，则给控制访问对象添加参数
            //if (param != null)
            //{
            //    this.command.CommandText = sql;
            //}
            this.command.CommandText = sql;
            //初始化sql语句，实例化适配器和数据集
            foreach (SqlParameter p in param)
            {
                this.command.Parameters.Add(p);
            }
            this.dataAdapter = new SqlDataAdapter(this.command);
            DataTable dt = new DataTable();

            //执行语句，如果错误，抛出异常。
            try
            {
                this.dataAdapter.Fill(dt);
            }
            catch (Exception ex)
            {
                throw new Exception(ex.Message);
            }
            finally
            {
                this.command.Dispose();
                this.dataAdapter.Dispose();
            }
            return dt;
        }
        public DataTable Select(string connectString, string sql, SqlParameter[] param)
        {
            //实例化一个控制访问对象，和连接对象，并初始化连接字符串
            this.command = new SqlCommand();
            this.connection = new SqlConnection(connectString);
            this.command.Connection = this.connection;

            //如果参数不为空，则给控制访问对象添加参数
            //if (param != null)
            //{
            //    this.command.CommandText = sql;
            //}
            this.command.CommandText = sql;
            //初始化sql语句，实例化适配器和数据集
            foreach (SqlParameter p in param)
            {
                this.command.Parameters.Add(p);
            }
            this.dataAdapter = new SqlDataAdapter(this.command);
            DataTable dt = new DataTable();

            //执行语句，如果错误，抛出异常。
            try
            {
                this.dataAdapter.Fill(dt);
            }
            catch (Exception ex)
            {
                throw new Exception(ex.Message);
            }
            finally
            {
                this.command.Dispose();
                this.dataAdapter.Dispose();
            }
            return dt;
        }
        #endregion 查询多行数据

        #region 查询多行数据

        /// <summary>
        /// 执行筛选操作
        /// </summary>
        /// <param name="sql">筛选的SQL语句</param>
        /// <param name="param">语句中所相关的参数</param>
        /// <returns>DataSet类型数据集</returns>
        public DataSet Select2(string sql, SqlParameter[] param)
        {
            //实例化一个控制访问对象，和连接对象，并初始化连接字符串
            this.command = new SqlCommand();
            this.connection = new SqlConnection(this.connectString);
            this.command.Connection = this.connection;

            //如果参数不为空，则给控制访问对象添加参数
            //if (param != null)
            //{
            //    
            //}
            this.command.CommandText = sql;
            //初始化sql语句，实例化适配器和数据集
            foreach (SqlParameter p in param)
            {
                this.command.Parameters.Add(p);
            }
            this.dataAdapter = new SqlDataAdapter(this.command);
            DataSet ds = new DataSet();

            //执行语句，如果错误，抛出异常。
            try
            {
                this.dataAdapter.Fill(ds);
            }
            catch (Exception ex)
            {
                throw new Exception(ex.Message);
            }
            finally
            {

                this.command.Dispose();
                this.dataAdapter.Dispose();
            }
            return ds;
        }

        #endregion 查询多行数据

        #region 查询一行数据

        /// <summary>
        /// 执行一条SQL语句，返回一个对象，该对象为语句的第一行第一列。
        /// </summary>
        /// <param name="sql">>筛选的sql语句</param>
        /// <param name="param">sql语句的相关参数</param>
        /// <returns>返回一个对象</returns>
        public object SelectOne(string sql, SqlParameter[] param)
        {
            //实例化一个控制语句和初始化SQL语句
            this.command = new SqlCommand();
            this.command.CommandText = sql;

            //为控制语句添加参数
            foreach (SqlParameter p in param)
            {
                this.command.Parameters.Add(p);
            }

            //打开连接，使控制访问对象获取当前连接
            this.Open();
            this.command.Connection = this.connection;

            //初始化一个对象，此将做返回。
            object o = null;

            //执行sql语句，如果发现异常抛出。
            try
            {
                o = this.command.ExecuteScalar();
            }
            catch (Exception ex)
            {
                throw new Exception(ex.Message);
            }
            finally
            {

                this.Close();
                this.command.Dispose();
            }
            return o;
        }

        #endregion 查询一行数据

        #region 更新数据、删除数据、添加数据

        /// <summary>
        /// 执行非查询语句
        /// </summary>
        /// <param name="sql">sql</param>
        /// <param name="param">sql的相关参数</param>
        /// <returns>返回影响行数</returns>
        public int UDIManager(string sql, SqlParameter[] param)
        {
            //实例化一个控制语句和初始化SQL语句
            this.command = new SqlCommand();
            this.command.CommandText = sql;

            //为控制语句添加参数
            foreach (SqlParameter p in param)
            {
                this.command.Parameters.Add(p);
            }

            //打开连接，使控制访问对象获取当前连接
            int e = -1;
            this.Open();
            this.command.Connection = this.connection;

            //执行sql语句，如果发现异常抛出。
            try
            {
                e = this.command.ExecuteNonQuery();
            }
            catch (Exception ex)
            {
                throw new Exception(ex.Message);
            }
            finally//关闭联机，销毁控制访问对象。
            {
                this.Close();
                this.command.Dispose();
            }
            return e;
        }

        /// <summary>
        /// 执行非查询语句（重载UDIManager）
        /// </summary>
        /// <param name="sql">SQL语句</param>
        /// <param name="param">参数</param>
        /// <param name="id">标识，判断是否返回ID</param>
        /// <returns>返回ID</returns>
        public int UDIManager(string sql, SqlParameter[] param, bool id)
        {
            //实例化一个控制语句和初始化SQL语句
            this.command = new SqlCommand();
            this.command.CommandText = sql + " SELECT @@IDENTITY";

            //为控制语句添加参数
            foreach (SqlParameter p in param)
            {
                this.command.Parameters.Add(p);
            }

            //打开连接，使控制访问对象获取当前连接，初始化影响行数（e=-1）.
            int e = -1;
            this.Open();
            this.command.Connection = this.connection;

            //执行sql语句，如果发现异常抛出。
            try
            {
                e = Convert.ToInt32(this.command.ExecuteScalar());
            }
            catch (Exception ex)
            {
                throw new Exception(ex.Message);
            }
            finally//关闭联机，销毁控制访问对象。
            {
                this.Close();
                this.command.Dispose();
            }
            return e;//返回结果
        }

        #endregion 更新数据、删除数据、添加数据

        #endregion 方法

        #region 存储过程

        // 用指定的参数值列表为存储过程参数赋值。
        private void AssignParameterValues(SqlCommand sqlCommand, params object[] paraValues)
        {
            if (paraValues != null)
            {
                if ((sqlCommand.Parameters.Count - 1) != paraValues.Length)
                {
                    throw new ArgumentNullException("The number of parameters does not match number of values for stored procedure  " + (sqlCommand.Parameters.Count - 1));
                }
                for (int i = 0; i < paraValues.Length; i++)
                {
                    sqlCommand.Parameters[i + 1].Value = (paraValues[i] == null) ? DBNull.Value : paraValues[i];
                }
            }
        }

        // 创建用于执行存储过程的 SqlCommand。
        private SqlCommand CreateSqlCommand(SqlConnection connection, string storeProcedureName)
        {
            SqlCommand command = new SqlCommand(storeProcedureName, connection);
            command.CommandType = CommandType.StoredProcedure;
            return command;
        }

        /// <summary>
        /// 从在 System.Data.SqlClient.SqlCommand 中指定的存储过程中检索参数信息并填充指定的
        /// System.Data.SqlClient.SqlCommand 对象的 System.Data.SqlClient.SqlCommand.Parameters 集  合。
        /// </summary>
        /// <param name="sqlCommand">将从其中导出参数信息的存储过程的 System.Data.SqlClient.SqlCommand 对象。</param>
        internal void DeriveParameters(SqlCommand sqlCommand)
        {
            try
            {
                sqlCommand.Connection.Open();
                SqlCommandBuilder.DeriveParameters(sqlCommand);
                sqlCommand.Connection.Close();
            }
            catch (Exception ex)
            {
                if (sqlCommand.Connection != null)
                {
                    sqlCommand.Connection.Close();
                }
                throw new Exception(ex.Message);
            }
        }

        /// <summary>
        /// 执行操作类（Insert/Delete/Update）存储过程。
        /// </summary>
        /// <param name="storeProcedureName">存储过程的名称</param>
        /// <param name="param">传递给存储过程的参数值列表。</param>
        /// <returns>受影响的行数。</returns>
        public int ExecuteNonQuery(string storeProcedureName, params object[] paraValues)
        {
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName);
                try
                {
                    this.DeriveParameters(command);
                    this.AssignParameterValues(command, paraValues);
                    connection.Open();
                    int affectedRowsCount = command.ExecuteNonQuery();
                    return affectedRowsCount;
                }
                catch (Exception ex)
                {
                    throw new Exception(ex.Message);
                }
            }
        }

        /// <summary>
        /// 执行存储过程，返回 System.Data.DataTable。
        /// </summary>
        /// <param name="storeProcedureName">存储过程的名称</param>
        /// <param name="param">传递给存储过程的参数值列表。</param>
        /// <returns>包含查询结果的 System.Data.DataTable。</returns>
        public DataTable ExecuteDataTable(string storeProcedureName, params object[] paraValues)
        {
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName);
                try
                {
                    this.DeriveParameters(command);
                    this.AssignParameterValues(command, paraValues);
                    SqlDataAdapter adapter = new SqlDataAdapter(command);
                    DataTable dataTable = new DataTable();
                    adapter.Fill(dataTable);
                    return dataTable;
                }
                catch (Exception ex)
                {
                    throw new Exception(ex.Message);
                }
            }
        }
        public DataTable ExecuteDataTable(string conString, string storeProcedureName, params object[] paraValues)
        {
            using (SqlConnection connection = new SqlConnection(conString))
            {
                SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName);
                try
                {
                    this.DeriveParameters(command);
                    this.AssignParameterValues(command, paraValues);
                    SqlDataAdapter adapter = new SqlDataAdapter(command);
                    DataTable dataTable = new DataTable();
                    adapter.Fill(dataTable);
                    return dataTable;
                }
                catch (Exception ex)
                {
                    throw new Exception(ex.Message);
                }
            }
        }

        /// <summary>
        /// 执行存储过程，返回 System.Data.DataSet。
        /// </summary>
        /// <param name="storeProcedureName">存储过程的名称</param>
        /// <param name="param">传递给存储过程的参数值列表。</param>
        /// <returns>包含查询结果的 System.Data.DataSet。</returns>
        public DataSet ExecuteDataSet(string storeProcedureName, params object[] paraValues)
        {
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName);
                command.CommandTimeout = 60;
                try
                {
                    this.DeriveParameters(command);
                    this.AssignParameterValues(command, paraValues);
                    SqlDataAdapter adapter = new SqlDataAdapter(command);
                    DataSet dataSet = new DataSet();
                    adapter.Fill(dataSet);
                    return dataSet;
                }
                catch (Exception ex)
                {
                    throw new Exception(ex.Message);
                }
            }
        }
        public DataSet ExecuteDataSet(string conString, string storeProcedureName, params object[] paraValues)
        {
            using (SqlConnection connection = new SqlConnection(conString))
            {
                SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName);
                command.CommandTimeout = 60;
                try
                {
                    this.DeriveParameters(command);
                    this.AssignParameterValues(command, paraValues);
                    SqlDataAdapter adapter = new SqlDataAdapter(command);
                    DataSet dataSet = new DataSet();
                    adapter.Fill(dataSet);
                    return dataSet;
                }
                catch (Exception ex)
                {
                    throw new Exception(ex.Message);
                }
            }
        }
        /// <summary>
        /// 执行存储过程，填充指定的 System.Data.DataTable。
        /// </summary>
        /// <param name="storeProcedureName">存储过程的名称</param>
        /// <param name="dataTable">用于填充查询结果的 System.Data.DataTable。</param>
        /// <param name="param">传递给存储过程的参数值列表。</param>
        public void ExecuteFillDataTable(string storeProcedureName, DataTable dataTable, params object[] paraValues)
        {
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName);
                try
                {
                    this.DeriveParameters(command);
                    this.AssignParameterValues(command, paraValues);
                    connection.Open();
                    SqlDataAdapter adapter = new SqlDataAdapter(command);
                    adapter.Fill(dataTable);
                }
                catch (Exception ex)
                {
                    throw new Exception(ex.Message);
                }
            }
        }

        /// <summary>
        /// 执行存储过程返回 System.Data.SqlClient.SqlDataReader，
        /// 在 System.Data.SqlClient.SqlDataReader 对象关闭时，数据库连接自动关闭。
        /// </summary>
        /// <param name="storeProcedureName">存储过程的名称</param>
        /// <param name="param">传递给存储过程的参数值列表。</param>
        /// <returns>包含查询结果的 System.Data.SqlClient.SqlDataReader 对象。</returns>
        public SqlDataReader ExecuteDataReader(string storeProcedureName, params object[] paraValues)
        {
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName);
                try
                {
                    this.DeriveParameters(command);
                    this.AssignParameterValues(command, paraValues);
                    connection.Open();
                    return command.ExecuteReader(CommandBehavior.CloseConnection);
                }
                catch (Exception ex)
                {
                    throw new Exception(ex.Message);
                }
            }
        }

        /// <summary>
        /// 执行查询，并返回查询所返回的结果集中第一行的第一列。忽略其他列或行。
        /// </summary>
        /// <param name="storeProcedureName">存储过程的名称</param>
        /// <param name="param">传递给存储过程的参数值列表。</param>
        /// <returns>结果集中第一行的第一列或空引用（如果结果集为空）。</returns>
        public object ExecuteScalar(string storeProcedureName, params object[] paraValues)
        {
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName);
                try
                {
                    this.DeriveParameters(command);
                    this.AssignParameterValues(command, paraValues);
                    connection.Open();
                    return command.ExecuteScalar();
                }
                catch (Exception ex)
                {
                    throw new Exception(ex.Message);
                }
            }
        }
        public object ExecuteScalar(string conString, string storeProcedureName, params object[] paraValues)
        {
            using (SqlConnection connection = new SqlConnection(conString))
            {
                SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName);
                try
                {
                    this.DeriveParameters(command);
                    this.AssignParameterValues(command, paraValues);
                    connection.Open();
                    return command.ExecuteScalar();
                }
                catch (Exception ex)
                {
                    throw new Exception(ex.Message);
                }
            }
        }
        #endregion 存储过程

        #region Jaswhen 20160123 增扩展方法
        /// <summary>
        /// 
        /// </summary>
        /// <param name="conString"></param>
        /// <param name="storeProcedureName"></param>
        /// <param name="paraValues"></param>
        /// <returns></returns>
        public DataSet ExecuteDataSetAuto(string storeProcedureName, params object[] paraValues)
        {
            return ExecuteDataSetAuto(this.connectString, storeProcedureName, paraValues);
        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="conString"></param>
        /// <param name="storeProcedureName"></param>
        /// <param name="paraValues"></param>
        /// <returns></returns>
        public DataSet ExecuteDataSetAuto(string conString, string storeProcedureName, params object[] paraValues)
        {
            using (SqlConnection connection = new SqlConnection(conString))
            {
                SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName);
                try
                {
                    AddInParaValues(command, paraValues);
                    //this.DeriveParameters(command);
                    //this.AssignParameterValues(command, paraValues);
                    SqlDataAdapter adapter = new SqlDataAdapter(command);
                    DataSet dataSet = new DataSet();
                    adapter.Fill(dataSet);
                    return dataSet;
                }
                catch (Exception ex)
                {
                    throw new Exception(ex.Message);
                }
            }
        }



        /// <summary>
        /// 获取存储过程的参数列表
        /// </summary>
        /// <param name="proc_Name">存储过程名称</param>
        /// <returns>DataTable</returns>
        private static DataTable GetParameters(SqlConnection connection, string proc_Name)
        {
            SqlCommand comm = new SqlCommand("dbo.sp_sproc_columns_90", connection);
            comm.CommandType = CommandType.StoredProcedure;
            comm.Parameters.AddWithValue("@procedure_name", (object)proc_Name);
            SqlDataAdapter sda = new SqlDataAdapter(comm);
            DataTable dt = new DataTable();
            sda.Fill(dt);
            return dt;
        }
        /// <summary>
        /// 为 SqlCommand 添加参数及赋值
        /// </summary>
        /// <param name="comm">SqlCommand</param>
        /// <param name="paraValues">参数数组(必须遵循存储过程参数列表的顺序)</param>
        private static void AddInParaValues(SqlCommand comm, params object[] paraValues)
        {
            comm.Parameters.Add(new SqlParameter("@RETURN_VALUE", SqlDbType.Int));
            comm.Parameters["@RETURN_VALUE"].Direction = ParameterDirection.ReturnValue;
            if (paraValues != null)
            {
                DataTable dt = GetParameters(comm.Connection, comm.CommandText);
                int i = 0;
                foreach (DataRow row in dt.Rows)
                {
                    string key = row[3].ToString();
                    if (key != "@RETURN_VALUE")
                    {
                        int value = int.Parse(row[4].ToString());
                        if (value == 1)
                        {
                            if (i < paraValues.Length)
                            {
                                comm.Parameters.AddWithValue(key, paraValues[i]);
                            }
                            else
                            {
                                comm.Parameters.AddWithValue(key, DBNull.Value);
                            }

                        }
                        else if (value == 2)//value为2则是输出参数
                        {
                            comm.Parameters.AddWithValue(key, paraValues[i]).Direction = ParameterDirection.Output;
                            //comm.Parameters[key].Direction = ParameterDirection.Output;
                        }
                        i++;
                    }
                }
            }
        }
        #endregion
    }
}