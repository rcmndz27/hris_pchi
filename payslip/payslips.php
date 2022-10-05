<?php 


function GetPayslipsList($action, $dtFrom, $dtTo,$empCode){


global $connL;

$query = "SELECT name,month_pay,daily_pay,semi_month_pay,tot_days_work as tdworkcnt,tot_days_work*daily_pay as tdworkamt,
(SELECT COUNT(rowid) from mf_holiday where holidaydate between :dfrhol1 and  :dthol1
and DATENAME(WEEKDAY,holidaydate) in ('Monday','Tuesday','Wednesday','Thursday','Friday')) as hdayscnt,
(SELECT COUNT(rowid) from mf_holiday where holidaydate between :dfrhol2 and  :dthol2
and DATENAME(WEEKDAY,holidaydate) in ('Monday','Tuesday','Wednesday','Thursday','Friday'))*daily_pay as hdaysamt,
b.vacation_leave+b.sick_leave as slvlcnt,a.vacation_leave+a.sick_leave as slvlamt,b.tot_lates as ltecnt,
a.late as lteamt,b.total_undertime as udcnt,a.undertime as udamt,b.tot_days_absent as abscnt,a.absences as absamt,
a.salary_adjustment as saladjamt,
tot_overtime_reg,
night_differential,
night_differential_ot,
tot_regholiday,
tot_overtime_regholiday,
tot_regholiday_nightdiff,
tot_overtime_regholiday_nightdiff,
tot_spholiday,
tot_overtime_spholiday,
tot_spholiday_nightdiff,
tot_overtime_spholiday_nightdiff,
tot_rest,
tot_overtime_rest,
night_differential_rest,
night_differential_ot_rest,
tot_overtime_rest_regholiday,
night_differential_rest_regholiday,
tot_overtime_night_diff_rest_regholiday,
tot_overtime_sprestholiday,
tot_sprestholiday_nightdiff,
tot_overtime_sprestholiday_nightdiff,
COALESCE((b.tot_overtime_reg * (((month_pay * 12) / 313)/8) * 1.25),0) as tot_overtime_regamt,
COALESCE((b.night_differential * (((month_pay * 12) / 313)/8) * 1.10),0) as night_differentialamt,
COALESCE((b.night_differential_ot * (((month_pay * 12) / 313)/8) * 1.375),0) as night_differential_otamt,
COALESCE((b.tot_regholiday * (((month_pay * 12) / 313)/8) * 2),0) as tot_regholidayamt,
COALESCE((b.tot_overtime_regholiday * (((month_pay * 12) / 313)/8) * 2.6),0) as tot_overtime_regholidayamt,
COALESCE((b.tot_regholiday_nightdiff * (((month_pay * 12) / 313)/8) * 2.2),0) as tot_regholiday_nightdiffamt,
COALESCE((b.tot_overtime_regholiday_nightdiff * (((month_pay * 12) / 313)/8) * 2.86),0) as tot_overtime_regholiday_nightdiffamt,
COALESCE((b.tot_spholiday * (((month_pay * 12) / 313)/8) * 1.3),0) as tot_spholidayamt,
COALESCE((b.tot_overtime_spholiday * (((month_pay * 12) / 313)/8) * 1.69),0) as tot_overtime_spholidayamt,
COALESCE((b.tot_spholiday_nightdiff * (((month_pay * 12) / 313)/8) * 1.43),0) as tot_spholiday_nightdiffamt,
COALESCE((b.tot_overtime_spholiday_nightdiff * (((month_pay * 12) / 313)/8) * 1.859),0) as tot_overtime_spholiday_nightdiffamt,
COALESCE((b.tot_rest * (((month_pay * 12) / 313)/8) * 1.30),0) as tot_restamt,
COALESCE((b.tot_overtime_rest * (((month_pay * 12) /313)/8) * 1.69),0) as tot_overtime_restamt,
COALESCE((b.night_differential_rest * (((month_pay * 12) /313)/8) * 1.43),0) as night_differential_restamt,
COALESCE((b.night_differential_ot_rest * (((month_pay * 12) /313)/8) * 1.859),0) as night_differential_ot_restamt,
COALESCE((b.tot_overtime_rest_regholiday * (((month_pay * 12) /313)/8) * 3.38),0) as tot_overtime_rest_regholidayamt,
COALESCE((b.night_differential_rest_regholiday * (((month_pay * 12) /313)/8) * 2.86),0) as night_differential_rest_regholidayamt,
COALESCE((b.tot_overtime_night_diff_rest_regholiday * (((month_pay * 12) /313)/8) * 3.718),0) as tot_overtime_night_diff_rest_regholidayamt,
COALESCE((b.tot_overtime_sprestholiday * (((month_pay * 12) /313)/8) * 1.95),0) as tot_overtime_sprestholidayamt,
COALESCE((b.tot_sprestholiday_nightdiff * (((month_pay * 12) /313)/8) * 1.65),0) as tot_sprestholiday_nightdiffamt,
COALESCE((b.tot_overtime_sprestholiday_nightdiff * (((month_pay * 12) /313)/8) * 2.145),0) as tot_overtime_sprestholiday_nightdiffamt,
a.trans_allowance as tralw,a.load_allowance as comalw,a.meal_allowance as mlalw,a.rel_allowance as relalw,
a.salary_allowance+a.oot_allowance+a.inc_allowance+a.disc_allowance as othrsalw,a.gross_pay,a.witholding_tax as whtax,
a.sss_regee as ssscont,a.phic_ee as phcont,a.hdmf_ee as pgbgcont,a.total_taxable as totaltax,
a.sss_sal_loan as ssssalloan,a.com_loan as comloan,a.sal_ded_nontax as salded,a.total_deduction as grsded,a.netpay as netpay,
c.earned_sl as sld,c.earned_vl as vld,c.earned_fl as fld
FROM payroll a left join att_summary b  on RIGHT(a.emp_code, LEN(a.emp_code) - 3) = b.badge_no
and a.date_from = b.period_from and a.date_to = b.period_to
left join employee_leave c on a.emp_code = c.emp_code
where a.date_from = :datefrom and a.date_to = :dateto and a.emp_code = :empcode";
$stmt =$connL->prepare($query);
$param = array(":empcode" => $empCode,":datefrom" => $dtFrom,":dateto" => $dtTo,":dfrhol1" => $dtFrom,":dthol1" => $dtTo,":dfrhol2" => $dtFrom,":dthol2" => $dtTo,);
$stmt->execute($param);
$r = $stmt->fetch();     

if($r){
do {

echo"<table id='payslipsList'><thead>
<tr>
<th colspan='6' style='text-align:center;'><img src='../img/pchi-logo.png' style='height:50px;'></th>
</tr>
<tr>
<th colspan='3'>NAME: ".$r['name']." </th>
<th colspan='3' class='camt'>PAYROLL PERIOD: ".$dtFrom." to ".$dtTo."</th>
</tr>
</thead>
<tbody>";

echo "<tr>".
"<th colspan='3' class='erdc'>EARNINGS</th>".
"<th colspan='3' class='erdc'>DEDUCTIONS</th>".
"</tr>
<tr>".
"<td class='ecl'>Description</td>".
"<td class='erdc'>Days/Hrs</td>".
"<td class='erdc'>Amount</td>".
"<td colspan='3' class='erdc'>Premium Deductions</td>". 
"</tr>
<tr>".
"<td>Total Days Worked:</td>".
"<td class='cnto'>".number_format($r['tdworkcnt'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tdworkamt'],2,".", ",")."</td>".                        
"<td colspan='2' class='erdc'>Witholding Tax:</td>". 
"<td colspan='1' class='cnto'>₱ ".number_format($r['whtax'],2,".", ",")."</td>". 
"</tr>                    
<tr>".
"<td>Holidays:</td>".
"<td class='cnto'>".number_format($r['hdayscnt'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['hdaysamt'],2,".", ",")."</td>".                         
"<td colspan='2' class='erdc'>SSS Contribution:</td>". 
"<td colspan='1' class='cnto'>₱ ".number_format($r['ssscont'],2,".", ",")."</td>". 
"</tr>                     
<tr>".
"<td>SLVL Amt:</td>".
"<td class='cnto'>".number_format($r['slvlcnt'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['slvlamt'],2,".", ",")."</td>".                          
"<td colspan='2' class='erdc'>Philhealth Contribution:</td>". 
"<td colspan='1' class='cnto'>₱ ".number_format($r['phcont'],2,".", ",")."</td>". 
"</tr> 
<tr>".
"<td>Late Amt:</td>".
"<td class='cnto'>".number_format($r['ltecnt'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['lteamt'],2,".", ",")."</td>".
"<td colspan='2' class='erdc'>Pagibig Contribution:</td>". 
"<td colspan='1' class='cnto'>₱ ".number_format($r['pgbgcont'],2,".", ",")."</td>". 
"</tr> 
<tr>".
"<td>Undertime Amt:</td>".
"<td class='cnto'>".number_format($r['udcnt'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['udamt'],2,".", ",")."</td>".
"<td colspan='3' class='erdc'></td>". 
"</tr>
<tr>".
"<td>Absent Amt:</td>".
"<td class='cnto'>".number_format($r['abscnt'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['absamt'],2,".", ",")."</td>".
"<td colspan='2' class='erdc'>Total Taxable:</td>". 
"<td colspan='1' class='cnto'>₱ ".number_format($r['totaltax'],2,".", ",")."</td>". 
"</tr>                      
<tr>".
"<td>Basic Adj. Amt:</td>".
"<td class='camt' colspan='2'>₱ ".number_format($r['saladjamt'],2,".", ",")."</td>".
"<td colspan='3' class='erdc'>Loan Balance and Deduction</td>". 
"</tr>  
<tr>".
"<td colspan='6'></td>".
"</tr> 
<tr colspan='6'>".
"<td class='erdc'>Basic Amount:</td>".
"<td class='camt' colspan='2'>₱ ".number_format($r['semi_month_pay'],2,".", ",")."</td>".
"<td colspan='2' class='erdc'>Description</td>". 
"<td colspan='1' class='erdc'>Amount</td>".
"</tr> 
<tr>".
"<td>Regular OT:</td>".
"<td class='cnto'>".number_format($r['tot_overtime_reg'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_overtime_regamt'],2,".", ",")."</td>".
"<td colspan='2' class='erdc'>SSS Salary Loan:</td>". 
"<td colspan='1' class='cnto'>₱ ".number_format($r['ssssalloan'],2,".", ",")."</td>". 
"</tr>
<tr>".
"<td>Regular Night Differential:</td>".
"<td class='cnto'>".number_format($r['night_differential'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['night_differentialamt'],2,".", ",")."</td>".
"<td colspan='2' class='erdc'>Company Loan:</td>". 
"<td colspan='1' class='cnto'>₱ ".number_format($r['comloan'],2,".", ",")."</td>".
"</tr>                                                                                                          
<tr>".
"<td>Regular Night Differential OT:</td>".
"<td class='cnto'>".number_format($r['night_differential_ot'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['night_differential_otamt'],2,".", ",")."</td>".
"<td colspan='2' class='erdc'>Salary Deduction:</td>". 
"<td colspan='1' class='cnto'>₱ ".number_format($r['salded'],2,".", ",")."</td>".
"</tr> 
<tr>".
"<td>Worked on Regular Holiday:</td>".
"<td class='cnto'>".number_format($r['tot_regholiday'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_regholidayamt'],2,".", ",")."</td>".
"<td colspan='3' class='erdc'></td>". 
"</tr> 
<tr>".
"<td>Regular Holiday OT:</td>".
"<td class='cnto'>".number_format($r['tot_overtime_regholiday'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_overtime_regholidayamt'],2,".", ",")."</td>".
"<td colspan='3' class='erdc'></td>". 
"</tr>
<tr>".
"<td>Regular Holiday Night Differential:</td>".
"<td class='cnto'>".number_format($r['tot_regholiday_nightdiff'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_regholiday_nightdiffamt'],2,".", ",")."</td>".
"<td colspan='3' class='erdc'></td>". 
"</tr>
<tr>".
"<td>Regular Holiday Night Differential OT:</td>".
"<td class='cnto'>".number_format($r['tot_overtime_regholiday_nightdiff'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_overtime_regholiday_nightdiffamt'],2,".", ",")."</td>".
"<td colspan='2' class='erdc'>Gross Deduction:</td>". 
"<td colspan='1' class='cnto'>₱ ".number_format($r['grsded'],2,".", ",")."</td>". 
"</tr>
<tr>".
"<td>Worked on Special Holiday:</td>".
"<td class='cnto'>".number_format($r['tot_spholiday'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_spholidayamt'],2,".", ",")."</td>".
"<td colspan='2' class='erdc'>NET PAY:</td>". 
"<td colspan='1' class='cnto'><b><u>₱ ".number_format($r['netpay'],2,".", ",")."</b></u></td>".  
"</tr> 
<tr>".
"<td>Special Holiday OT:</td>".
"<td class='cnto'>".number_format($r['tot_overtime_spholiday'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_overtime_spholidayamt'],2,".", ",")."</td>".
"<td colspan='3' class='erdc'>Leave Balances</td>".  
"</tr>
<tr>".
"<td>Special Holiday Night Differential:</td>".
"<td class='cnto'>".number_format($r['tot_spholiday_nightdiff'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_spholiday_nightdiffamt'],2,".", ",")."</td>".
"<td colspan='2' class='erdc'>Sick Leave Days</td>". 
"<td colspan='1' class='cnto'>".number_format($r['sld'],2,".", ",")."</td>".  
"</tr>
<tr>".
"<td>Special Holiday Night Differential OT:</td>".
"<td class='cnto'>".number_format($r['tot_overtime_regholiday_nightdiff'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_overtime_spholiday_nightdiffamt'],2,".", ",")."</td>".
"<td colspan='2' class='erdc'>Vacation Leave Days</td>". 
"<td colspan='1' class='cnto'>".number_format($r['vld'],2,".", ",")."</td>".   
"</tr>                      
<tr>".
"<td>Worked on Rest Day:</td>".
"<td class='cnto'>".number_format($r['tot_rest'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_restamt'],2,".", ",")."</td>".
"<td colspan='2' class='erdc'>Floating Leave Days</td>". 
"<td colspan='1' class='cnto'>".number_format($r['fld'],2,".", ",")."</td>".  
"</tr> 
<tr>".
"<td>Rest Day OT:</td>".
"<td class='cnto'>".number_format($r['tot_overtime_rest'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_overtime_restamt'],2,".", ",")."</td>".
"</tr>
<tr>".
"<td>Rest Day Night Differential:</td>".
"<td class='cnto'>".number_format($r['night_differential_rest'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['night_differential_restamt'],2,".", ",")."</td>".
"</tr>
<tr>".
"<td>Rest Day Night Differential OT:</td>".
"<td class='cnto'>".number_format($r['night_differential_ot_rest'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['night_differential_ot_restamt'],2,".", ",")."</td>".
"</tr>
<tr>".
"<td>Rest Day Regular Holiday OT:</td>".
"<td class='cnto'>".number_format($r['tot_overtime_rest_regholiday'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_overtime_rest_regholidayamt'],2,".", ",")."</td>".
// "<td colspan='3' class='erdc'></td>". 
"</tr>
<tr>".
"<td>Rest Day Regular Holiday Night Differential:</td>".
"<td class='cnto'>".number_format($r['night_differential_rest_regholiday'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['night_differential_rest_regholidayamt'],2,".", ",")."</td>".
// "<td colspan='3' class='erdc'></td>". 
"</tr>
<tr>".
"<td>Rest Day Regular Holiday Night Differential OT:</td>".
"<td class='cnto'>".number_format($r['tot_overtime_night_diff_rest_regholiday'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_overtime_night_diff_rest_regholidayamt'],2,".", ",")."</td>".
"</tr>  
<tr>".
"<td>Rest Day Regular Holiday OT:</td>".
"<td class='cnto'>".number_format($r['tot_overtime_rest_regholiday'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_overtime_rest_regholidayamt'],2,".", ",")."</td>".

"</tr>
<tr>".
"<td>Rest Day Regular Holiday Night Differential:</td>".
"<td class='cnto'>".number_format($r['night_differential_rest_regholiday'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['night_differential_rest_regholidayamt'],2,".", ",")."</td>".
// "<td colspan='3' class='erdc'></td>". 
"</tr>
<tr>".
"<td>Rest Day Regular Holiday Night Differential OT:</td>".
"<td class='cnto'>".number_format($r['tot_overtime_night_diff_rest_regholiday'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_overtime_night_diff_rest_regholidayamt'],2,".", ",")."</td>".
// "<td colspan='3' class='erdc'></td>". 
"</tr>
<tr>".
"<td>Rest Day Special Holiday OT:</td>".
"<td class='cnto'>".number_format($r['tot_overtime_sprestholiday'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_overtime_sprestholidayamt'],2,".", ",")."</td>".
// "<td colspan='3' class='erdc'></td>". 
"</tr>
<tr>".
"<td>Rest Day Special Holiday Night Differential:</td>".
"<td class='cnto'>".number_format($r['tot_sprestholiday_nightdiff'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_sprestholiday_nightdiffamt'],2,".", ",")."</td>".
// "<td colspan='3' class='erdc'></td>". 
"</tr>
<tr>".
"<td>Rest Day Special Holiday Night Differential OT:</td>".
"<td class='cnto'>".number_format($r['tot_overtime_sprestholiday_nightdiff'],2,".", ",")."</td>".
"<td class='camt'>₱ ".number_format($r['tot_overtime_sprestholiday_nightdiffamt'],2,".", ",")."</td>".
// "<td colspan='3' class='erdc'></td>". 
"</tr>   
<tr>".
"<td>Travel Allowance</td>".
"<td class='camt' colspan='2'>₱ ".number_format($r['tralw'],2,".", ",")."</td>".
// "<td colspan='3' class='erdc'></td>". 
"</tr>
<tr>".
"<td>Communication Allowance</td>".
"<td class='camt' colspan='2'>₱ ".number_format($r['comalw'],2,".", ",")."</td>".
// "<td colspan='3' class='erdc'></td>". 
"</tr> 
<tr>".
"<td>Meal Allowance</td>".
"<td class='camt' colspan='2'>₱ ".number_format($r['mlalw'],2,".", ",")."</td>".
// "<td colspan='3' class='erdc'></td>". 
"</tr>
<tr>".
"<td>Relocation Allowance</td>".
"<td class='camt' colspan='2'>₱ ".number_format($r['relalw'],2,".", ",")."</td>".
// "<td colspan='3' class='erdc'></td>". 
"</tr> 
<tr>".
"<td>Other Allowance</td>".
"<td class='camt' colspan='2'>₱ ".number_format($r['othrsalw'],2,".", ",")."</td>".
// "<td colspan='3' class='erdc'></td>". 
"</tr>                                                                                                          
<tr>".
"<td colspan='6'>       </td>".
"</tr>  
<tr>".
"<td class='erdc'>GROSS EARNINGS</td>".
"<td class='camt' colspan='2'>₱ ".number_format($r['gross_pay'],2,".", ",")."</td>".
// "<td colspan='3' class='erdc'></td>". 
"</tr>                                                                              
";



} while($r = $stmt->fetch(PDO::FETCH_ASSOC));

// echo"</tbody>";
//       echo "<tfoot></tfoot>";    

}else { 

echo '<tfoot><tr><td colspan="11" class="cnt"><button id="showpay" value="notok" hidden></button>No Payslip Found</td></tr></tfoot>'; 
}

echo"</table>"; 



}


?>
