using System;
using System.Collections.Generic;
using System.Data;
using System.Data.SqlClient;

namespace Utils
{
    /// <summary>
    ///  SQL Server 数据库 操作类
    /// </summary>
    public class DBHelper
    {
        /// <summary>
        /// 连接字符串,示例：connectString = "Data Source=数据库地址;Initial Catalog=数据库名称;User ID=数据库账号;Password=数据库密码";
        /// </summary>
        public string connectString { get; set; }

        /// <summary>
        /// 默认超时时间180s
        /// </summary>
        private int commandTimeout = 180;

        public DBHelper(string _connectString)
        {
            this.connectString = _connectString;
        }

        /// <summary>
        /// 创建用于执行存储过程的 SqlCommand。
        /// </summary>
        /// <param name="connection"></param>
        /// <param name="storeProcedureName"></param>
        /// <returns></returns>
        private SqlCommand CreateSqlCommand(SqlConnection connection, string storeProcedureName, SqlTransaction trans = null)
        {
            SqlCommand command = new SqlCommand(storeProcedureName, connection);
            command.CommandType = CommandType.StoredProcedure;
            command.CommandTimeout = this.commandTimeout;//默认超时时间
            if (trans != null)
            { command.Transaction = trans; }
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
        /// 用指定的参数值列表为存储过程参数赋值。
        /// </summary>
        /// <param name="sqlCommand"></param>
        /// <param name="paraValues"></param>
        private void AssignParameterValues(bool autoFillParam, SqlCommand sqlCommand, params object[] paraValues)
        {
            if (paraValues != null)
            {
                if (autoFillParam == true)
                {
                    int i = 0;
                    foreach (SqlParameter parameter in sqlCommand.Parameters)
                    {
                        if (!parameter.ParameterName.Equals("@RETURN_VALUE"))
                        {
                            if (i < paraValues.Length)
                            {
                                sqlCommand.Parameters[i + 1].Value = (paraValues[i] == null) ? DBNull.Value : paraValues[i];
                            }
                            else
                            {
                                sqlCommand.Parameters[i + 1].Value = DBNull.Value;
                            }
                            i++;
                        }
                    }
                }
                else
                {
                    //Count - 1 是因为 第一个参数是 @RETURN_VALUE 这个是返回return 值使用
                    //使用 dbo.sp_sproc_columns 存储过程名 可以查看存储过程接口信息
                    if ((sqlCommand.Parameters.Count - 1) != paraValues.Length)
                    {
                        throw new ArgumentNullException("The number of parameters does not match number of values for stored procedure  " + (sqlCommand.Parameters.Count - 1));
                    }

                    for (int i = 0; i < paraValues.Length; i++)
                    {
                        //Parameters[i + 1] 是因为 第一个参数是 @RETURN_VALUE
                        sqlCommand.Parameters[i + 1].Value = (paraValues[i] == null) ? DBNull.Value : paraValues[i];
                    }
                }
            }
        }

        /// <summary>
        /// 用指定的参数值列表为存储过程参数赋值。
        /// </summary>
        /// <param name="sqlCommand"></param>
        /// <param name="parameters"></param>
        private void AssignParameterValues(SqlCommand sqlCommand, IDataParameter[] parameters)
        {
            foreach (SqlParameter parameter in parameters)
            {
                if (parameter != null)
                {
                    // 检查未分配值的输出参数,将其分配以DBNull.Value.
                    if ((parameter.Direction == ParameterDirection.InputOutput || parameter.Direction == ParameterDirection.Input) &&
                        (parameter.Value == null))
                    {
                        parameter.Value = DBNull.Value;
                    }
                    sqlCommand.Parameters.Add(parameter);
                }
            }
        }

        /// <summary>
        /// 执行事务发生错误时范围值
        /// </summary>
        private DataSet ExecuteTransToError(string Message)
        {
            DataSet dataSet = new DataSet();
            DataTable dataTable = new DataTable();
            dataTable.Columns.Add("Code", Type.GetType("System.Int32"));
            dataTable.Columns.Add("Message", Type.GetType("System.String"));
            DataRow row = dataTable.NewRow();
            row["Code"] = 0;
            row["Message"] = Message;
            dataTable.Rows.Add(row);//将此行添加到table中 
            dataSet.Tables.Add(dataTable);
            return dataSet;
        }
        /// <summary>
        /// 执行事务批量存储过程是发生错误时范围值
        /// </summary>
        private List<SortedDictionary<string, DataSet>> ExecuteBatchTransToError(string Message)
        {
            DataSet dataSet = new DataSet();
            DataTable dataTable = new DataTable();
            dataTable.Columns.Add("Code", Type.GetType("System.Int32"));
            dataTable.Columns.Add("Message", Type.GetType("System.String"));
            DataRow row = dataTable.NewRow();
            row["Code"] = 0;
            row["Message"] = Message;
            dataTable.Rows.Add(row);//将此行添加到table中 
            dataSet.Tables.Add(dataTable);

            List<SortedDictionary<string, DataSet>> ReturnList = new List<SortedDictionary<string, DataSet>>();
            SortedDictionary<string, DataSet> ReturnDataSet = new SortedDictionary<string, DataSet>();
            ReturnDataSet.Add("Error", dataSet);
            ReturnList.Add(ReturnDataSet);
            return ReturnList;
        }
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

        #region 存储过程
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

        public DataSet ExecuteProcedure(string storeProcedureName, params object[] paraValues)
        {
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName);

                try
                {
                    //检索存储过程所需参数相关信息
                    this.DeriveParameters(command);
                    //填充参数
                    this.AssignParameterValues(false, command, paraValues);

                    SqlDataAdapter adapter = new SqlDataAdapter(command);
                    DataSet dataSet = new DataSet();
                    adapter.Fill(dataSet);
                    return dataSet;
                }
                catch (System.Exception ex)
                {
                    throw new Exception(ex.Message);
                }
            }
        }

        public DataSet ExecuteProcedure(string storeProcedureName, IDataParameter[] parameters)
        {
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName);

                try
                {
                    //填充参数
                    this.AssignParameterValues(command, parameters);

                    SqlDataAdapter adapter = new SqlDataAdapter(command);
                    DataSet dataSet = new DataSet();
                    adapter.Fill(dataSet);
                    return dataSet;
                }
                catch (System.Exception ex)
                {
                    throw new Exception(ex.Message);
                }
            }
        }

        public DataSet ExecuteProcedure(string storeProcedureName, IDataParameter[] parameters, string tableName)
        {
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName);

                try
                {
                    //填充参数
                    this.AssignParameterValues(command, parameters);

                    SqlDataAdapter adapter = new SqlDataAdapter(command);
                    DataSet dataSet = new DataSet();
                    adapter.Fill(dataSet, tableName);
                    return dataSet;
                }
                catch (System.Exception ex)
                {
                    throw new Exception(ex.Message);
                }
            }
        }

        public DataSet ExecuteProcedure(string storeProcedureName, IDataParameter[] parameters, string tableName, int Times)
        {
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName);

                try
                {
                    //填充参数
                    this.AssignParameterValues(command, parameters);
                    command.CommandTimeout = Times;//超时时间

                    SqlDataAdapter adapter = new SqlDataAdapter(command);
                    DataSet dataSet = new DataSet();
                    adapter.Fill(dataSet, tableName);
                    return dataSet;
                }
                catch (System.Exception ex)
                {
                    throw new Exception(ex.Message);
                }
            }
        }
        #endregion
        #region 存储过程(含事务)
        public DataSet ExecuteProcedureTrans(string storeProcedureName, params object[] paraValues)
        {
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                connection.Open();
                using (SqlTransaction trans = connection.BeginTransaction())
                {
                    SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName, trans);

                    try
                    {
                        //检索存储过程所需参数相关信息
                        this.DeriveParameters(command);
                        //填充参数
                        this.AssignParameterValues(false, command, paraValues);

                        SqlDataAdapter adapter = new SqlDataAdapter(command);
                        DataSet dataSet = new DataSet();
                        adapter.Fill(dataSet);

                        trans.Commit();
                        return dataSet;
                    }
                    catch (System.Exception ex)
                    {
                        try
                        {
                            trans.Rollback();
                            throw new Exception(ex.Message);
                        }
                        catch (SqlException exhg)
                        {
                            if (trans.Connection != null)
                            {
                                throw new Exception(ex.Message + "|" + exhg.Message);
                            }
                        }

                        return ExecuteTransToError(ex.Message);
                    }
                }
            }
        }

        public DataSet ExecuteProcedureTrans(string storeProcedureName, IDataParameter[] parameters)
        {
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                connection.Open();
                using (SqlTransaction trans = connection.BeginTransaction())
                {
                    SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName, trans);

                    try
                    {
                        //填充参数
                        this.AssignParameterValues(command, parameters);

                        SqlDataAdapter adapter = new SqlDataAdapter(command);
                        DataSet dataSet = new DataSet();
                        adapter.Fill(dataSet);

                        trans.Commit();
                        return dataSet;
                    }
                    catch (System.Exception ex)
                    {
                        try
                        {
                            trans.Rollback();
                            throw new Exception(ex.Message);
                        }
                        catch (SqlException exhg)
                        {
                            if (trans.Connection != null)
                            {
                                throw new Exception(ex.Message + "|" + exhg.Message);
                            }
                        }

                        return ExecuteTransToError(ex.Message);
                    }
                }
            }
        }

        public DataSet ExecuteProcedureTrans(string storeProcedureName, IDataParameter[] parameters, string tableName)
        {
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                connection.Open();
                using (SqlTransaction trans = connection.BeginTransaction())
                {
                    SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName, trans);

                    try
                    {
                        //填充参数
                        this.AssignParameterValues(command, parameters);

                        SqlDataAdapter adapter = new SqlDataAdapter(command);
                        DataSet dataSet = new DataSet();
                        adapter.Fill(dataSet, tableName);

                        trans.Commit();
                        return dataSet;
                    }
                    catch (System.Exception ex)
                    {
                        try
                        {
                            trans.Rollback();
                            throw new Exception(ex.Message);
                        }
                        catch (SqlException exhg)
                        {
                            if (trans.Connection != null)
                            {
                                throw new Exception(ex.Message + "|" + exhg.Message);
                            }
                        }

                        return ExecuteTransToError(ex.Message);
                    }
                }
            }
        }

        public DataSet ExecuteProcedureTrans(string storeProcedureName, IDataParameter[] parameters, string tableName, int Times)
        {
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                connection.Open();
                using (SqlTransaction trans = connection.BeginTransaction())
                {
                    SqlCommand command = this.CreateSqlCommand(connection, storeProcedureName, trans);

                    try
                    {
                        //填充参数
                        this.AssignParameterValues(command, parameters);
                        command.CommandTimeout = Times;//超时时间

                        SqlDataAdapter adapter = new SqlDataAdapter(command);
                        DataSet dataSet = new DataSet();
                        adapter.Fill(dataSet, tableName);

                        trans.Commit();
                        return dataSet;
                    }
                    catch (System.Exception ex)
                    {
                        try
                        {
                            trans.Rollback();
                            throw new Exception(ex.Message);
                        }
                        catch (SqlException exhg)
                        {
                            if (trans.Connection != null)
                            {
                                throw new Exception(ex.Message + "|" + exhg.Message);
                            }
                        }

                        return ExecuteTransToError(ex.Message);
                    }
                }
            }
        }
        #endregion

        #region 批量执行存储过程(含事务)
        public List<SortedDictionary<string, DataSet>> ExecuteBatchProcedureTrans(List<SortedDictionary<string, IDataParameter[]>> StoredProcList)
        {
            List<SortedDictionary<string, DataSet>> ReturnList = new List<SortedDictionary<string, DataSet>>();
            using (SqlConnection connection = new SqlConnection(this.connectString))
            {
                connection.Open();
                using (SqlTransaction trans = connection.BeginTransaction())
                {
                    try
                    {
                        foreach (SortedDictionary<string, IDataParameter[]> StoredProcDictionary in StoredProcList)
                        {
                            foreach (KeyValuePair<string, IDataParameter[]> Procedure in StoredProcDictionary)
                            {

                                //        IDataParameter[] parameters = SDStoredProc[storeProcedureName];
                                SqlCommand command = this.CreateSqlCommand(connection, Procedure.Key, trans);


                                //填充参数
                                this.AssignParameterValues(command, Procedure.Value);

                                SqlDataAdapter adapter = new SqlDataAdapter(command);
                                DataSet dataSet = new DataSet();
                                adapter.Fill(dataSet);
                                SortedDictionary<string, DataSet> ReturnDataSet = new SortedDictionary<string, DataSet>();
                                ReturnDataSet.Add(Procedure.Key, dataSet);
                                ReturnList.Add(ReturnDataSet);
                            }
                        }

                        trans.Commit();
                        return ReturnList;
                    }
                    catch (System.Exception ex)
                    {
                        try
                        {
                            trans.Rollback();
                            throw new Exception(ex.Message);
                        }
                        catch (SqlException exhg)
                        {
                            if (trans.Connection != null)
                            {
                                throw new Exception(ex.Message + "|" + exhg.Message);
                            }
                        }

                        return ExecuteBatchTransToError(ex.Message);
                    }
                }
            }
        }

        #endregion

    }
}
