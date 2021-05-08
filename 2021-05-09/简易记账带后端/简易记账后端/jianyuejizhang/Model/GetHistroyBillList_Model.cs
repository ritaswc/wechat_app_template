using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace jianyuejizhang.Model
{
    public class GetHistroyBillList_Model : Base_Model
    {
        public List<Bill> BillList  = new List<Bill>();

        public class Bill{
            public string UserNo { get; set; }
            public string UserName { get; set; }
            public string BillNo { get; set; }
            public string BillDate { get; set; }
            public double BillMoney { get; set; }
            public string BillPurpose { get; set; }
            public string BillRemark { get; set; }
           
        }

    }
}