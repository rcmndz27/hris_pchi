<?php
//include library
include('../lib_tcpdf/tcpdf.php');
include('../config/db.php');
//make TCPDF object
$pdf = new TCPDF('P','mm','A4');

//remove default header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//add page
$pdf->AddPage();

//add content (student list)
//title
$pdf->SetFont('Helvetica','',14);
$pdf->Cell(190,10,"University of Insert Name Here",0,1,'C');

$pdf->SetFont('Helvetica','',8);
$pdf->Cell(190,5,"Student List",0,1,'C');

$pdf->SetFont('Helvetica','',10);
$pdf->Cell(30,5,"Class",0);
$pdf->Cell(160,5,": Programming 101",0);
$pdf->Ln();
$pdf->Cell(30,5,"Teacher Name",0);
$pdf->Cell(160,5,": Prof. John Smith",0);
$pdf->Ln();
$pdf->Ln(2);

           
global $connL;

$cmd = $connL->prepare('EXEC payslip_summary :date_start, :date_end ,:emp_code');
$cmd->bindValue(':date_start','04/08/2022');
$cmd->bindValue(':date_end','04/22/2022');
$cmd->bindValue(':emp_code','PCH21000508');
$cmd->execute();
$r = $cmd->fetch();

if($r){
          do {

                $html ="<table id='payslipsList'><thead>
                    <tr>
                        <th colspan='6' style='text-align:center;'><img src='../img/pchi-logo.png' style='height:50px;'></th>
                        <button id='showpay' value='ok' hidden></button>
                    </tr>
                    <tr>
                        <th colspan='3'>EMPLOYEE NAME </th>
                        <th colspan='3' class='camt'>PAYROLL PERIOD:</th>
                    </tr>
                </thead>
                <tbody>";

                $totworkamt = $r['tot_days_work']*$r['daily_pay'];
                $totslvl = $r['sl']+$r['vl'];
                $totslvl_amt = ($r['sl']+$r['vl'])*$r['daily_pay'];
                $others = $r['salary_allowance']+$r['oot_allowance']+$r['inc_allowance']+$r['disc_allowance'];

                $html .= "<tr>".
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
                        "<td>Regular Days:</td>".
                        "<td class='cnto'>".number_format($r['tot_days_work'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($totworkamt,2,".", ",")."</td>".
                        "<td colspan='2' class='erdc'>Witholding Tax:</td>". 
                        "<td colspan='1' class='cnto'>₱ ".number_format($r['witholding_tax'],2,".", ",")."</td>". 
                    "</tr>                    
                    <tr>".
                        "<td>Holidays:</td>".
                        "<td class='cnto'>0.00</td>".
                        "<td class='camt'>₱ 0.00</td>".
                        "<td colspan='2' class='erdc'>SSS Contribution:</td>". 
                        "<td colspan='1' class='cnto'>₱ ".number_format($r['sss_regee'],2,".", ",")."</td>". 
                    "</tr>                     
                    <tr>".
                        "<td>SLVL Amt:</td>".
                        "<td class='cnto'>".number_format($totslvl,2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($totslvl_amt,2,".", ",")."</td>".
                        "<td colspan='2' class='erdc'>Philhealth Contribution:</td>". 
                        "<td colspan='1' class='cnto'>₱ ".number_format($r['phic_ee'],2,".", ",")."</td>". 
                    "</tr> 
                    <tr>".
                        "<td>Late Amt:</td>".
                        "<td class='cnto'>".number_format($r['tot_lates'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['late'],2,".", ",")."</td>".
                        "<td colspan='2' class='erdc'>Pagibig Contribution:</td>". 
                        "<td colspan='1' class='cnto'>₱ ".number_format($r['hdmf_ee'],2,".", ",")."</td>". 
                    "</tr> 
                    <tr>".
                        "<td>Undertime Amt:</td>".
                        "<td class='cnto'>".number_format($r['total_undertime'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['undertime'],2,".", ",")."</td>".
                        "<td colspan='3' class='erdc'></td>". 
                    "</tr> 
                    <tr>".
                        "<td>Basic Adj. Amt:</td>".
                        "<td class='camt' colspan='2'>₱ ".number_format($r['salary_adjustment'],2,".", ",")."</td>".
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
                        "<td class='cnto'>".number_format($r['att_tot_overtime_reg'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_overtime_reg'],2,".", ",")."</td>".
                        "<td colspan='3' class='erdc'></td>". 
                    "</tr>
                    <tr>".
                        "<td>Regular Night Differential:</td>".
                        "<td class='cnto'>".number_format($r['att_night_differential'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['night_differential'],2,".", ",")."</td>".
                        "<td colspan='3' class='erdc'></td>". 
                    "</tr>                                                                                                          
                    <tr>".
                        "<td>Regular Night Differential OT:</td>".
                        "<td class='cnto'>".number_format($r['att_night_differential_ot'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['night_differential_ot'],2,".", ",")."</td>".
                        "<td colspan='3' class='erdc'></td>". 
                    "</tr> 
                    <tr>".
                        "<td>Worked on Regular Holiday:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_regholiday'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_regholiday'],2,".", ",")."</td>".
                        "<td colspan='3' class='erdc'></td>". 
                    "</tr> 
                    <tr>".
                        "<td>Regular Holiday OT:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_overtime_regholiday'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_overtime_regholiday'],2,".", ",")."</td>".
                        "<td colspan='3' class='erdc'></td>". 
                    "</tr>
                    <tr>".
                        "<td>Regular Holiday Night Differential:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_regholiday_nightdiff'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_regholiday_nightdiff'],2,".", ",")."</td>".
                        "<td colspan='3' class='erdc'></td>". 
                    "</tr>
                    <tr>".
                        "<td>Regular Holiday Night Differential OT:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_overtime_regholiday_nightdiff'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_overtime_regholiday_nightdiff'],2,".", ",")."</td>".
                        "<td colspan='2' class='erdc'>Gross Deduction:</td>". 
                        "<td colspan='1' class='cnto'>₱ ".number_format($r['total_deduction'],2,".", ",")."</td>". 
                    "</tr>
                    <tr>".
                        "<td>Worked on Special Holiday:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_spholiday'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_spholiday'],2,".", ",")."</td>".
                        "<td colspan='2' class='erdc'>NET PAY:</td>". 
                        "<td colspan='1' class='cnto'>₱ ".number_format($r['netpay'],2,".", ",")."</td>".  
                    "</tr> 
                    <tr>".
                        "<td>Special Holiday OT:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_overtime_spholiday'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_overtime_spholiday'],2,".", ",")."</td>".
                        "<td colspan='3' class='erdc'>Leave Balances</td>".  
                    "</tr>
                    <tr>".
                        "<td>Special Holiday Night Differential:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_spholiday_nightdiff'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_spholiday_nightdiff'],2,".", ",")."</td>".
                        "<td colspan='2' class='erdc'>Sick Leave Days</td>". 
                        "<td colspan='1' class='cnto'>".number_format($r['earned_sl'],2,".", ",")."</td>".  
                    "</tr>
                    <tr>".
                        "<td>Special Holiday Night Differential OT:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_overtime_regholiday_nightdiff'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_overtime_spholiday_nightdiff'],2,".", ",")."</td>".
                        "<td colspan='2' class='erdc'>Vacation Leave Days</td>". 
                        "<td colspan='1' class='cnto'>".number_format($r['earned_vl'],2,".", ",")."</td>".   
                    "</tr>                      
                    <tr>".
                        "<td>Worked on Rest Day:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_rest'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_rest'],2,".", ",")."</td>".
                        "<td colspan='2' class='erdc'>Emergency Leave Days</td>". 
                        "<td colspan='1' class='cnto'>0.00</td>". 
                    "</tr> 
                    <tr>".
                        "<td>Rest Day OT:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_overtime_rest'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_overtime_rest'],2,".", ",")."</td>".
                        "<td colspan='2' class='erdc'>Paternity Leave Days</td>". 
                        "<td colspan='1' class='cnto'>0.00</td>".
                    "</tr>
                    <tr>".
                        "<td>Rest Day Night Differential:</td>".
                        "<td class='cnto'>".number_format($r['att_night_differential_rest'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['night_differential_rest'],2,".", ",")."</td>".
                        "<td colspan='2' class='erdc'>Maternity Leave Days</td>". 
                        "<td colspan='1' class='cnto'>0.00</td>". 
                    "</tr>
                    <tr>".
                        "<td>Rest Day Night Differential OT:</td>".
                        "<td class='cnto'>".number_format($r['att_night_differential_ot_rest'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['night_differential_ot_rest'],2,".", ",")."</td>".
                        // "<td colspan='3' class='erdc'></td>". 
                    "</tr>
                    <tr>".
                        "<td>Rest Day Regular Holiday OT:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_overtime_rest_regholiday'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_overtime_rest_regholiday'],2,".", ",")."</td>".
                        // "<td colspan='3' class='erdc'></td>". 
                    "</tr>
                    <tr>".
                        "<td>Rest Day Regular Holiday Night Differential:</td>".
                        "<td class='cnto'>".number_format($r['att_night_differential_rest_regholiday'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['night_differential_rest_regholiday'],2,".", ",")."</td>".
                        // "<td colspan='3' class='erdc'></td>". 
                    "</tr>
                    <tr>".
                        "<td>Rest Day Regular Holiday Night Differential OT:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_overtime_night_diff_rest_regholiday'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_overtime_night_diff_rest_regholiday'],2,".", ",")."</td>".
                    "</tr>  
                    <tr>".
                        "<td>Rest Day Regular Holiday OT:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_overtime_rest_regholiday'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_overtime_rest_regholiday'],2,".", ",")."</td>".

                    "</tr>
                    <tr>".
                        "<td>Rest Day Regular Holiday Night Differential:</td>".
                        "<td class='cnto'>".number_format($r['att_night_differential_rest_regholiday'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['night_differential_rest_regholiday'],2,".", ",")."</td>".
                        // "<td colspan='3' class='erdc'></td>". 
                    "</tr>
                    <tr>".
                        "<td>Rest Day Regular Holiday Night Differential OT:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_overtime_night_diff_rest_regholiday'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_overtime_night_diff_rest_regholiday'],2,".", ",")."</td>".
                        // "<td colspan='3' class='erdc'></td>". 
                    "</tr>
                    <tr>".
                        "<td>Rest Day Special Holiday OT:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_overtime_sprestholiday'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_overtime_sprestholiday'],2,".", ",")."</td>".
                        // "<td colspan='3' class='erdc'></td>". 
                    "</tr>
                    <tr>".
                        "<td>Rest Day Special Holiday Night Differential:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_sprestholiday_nightdiff'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_sprestholiday_nightdiff'],2,".", ",")."</td>".
                        // "<td colspan='3' class='erdc'></td>". 
                    "</tr>
                    <tr>".
                        "<td>Rest Day Special Holiday Night Differential OT:</td>".
                        "<td class='cnto'>".number_format($r['att_tot_overtime_sprestholiday_nightdiff'],2,".", ",")."</td>".
                        "<td class='camt'>₱ ".number_format($r['tot_overtime_sprestholiday_nightdiff'],2,".", ",")."</td>".
                        // "<td colspan='3' class='erdc'></td>". 
                    "</tr>   
                    <tr>".
                        "<td>Travel Allowance</td>".
                        "<td class='camt' colspan='2'>₱ ".number_format($r['trans_allowance'],2,".", ",")."</td>".
                        // "<td colspan='3' class='erdc'></td>". 
                    "</tr>
                    <tr>".
                        "<td>Communication Allowance</td>".
                        "<td class='camt' colspan='2'>₱ ".number_format($r['load_allowance'],2,".", ",")."</td>".
                        // "<td colspan='3' class='erdc'></td>". 
                    "</tr> 
                    <tr>".
                        "<td>Meal Allowance</td>".
                        "<td class='camt' colspan='2'>₱ ".number_format($r['meal_allowance'],2,".", ",")."</td>".
                        // "<td colspan='3' class='erdc'></td>". 
                    "</tr>
                    <tr>".
                        "<td>Relocation Allowance</td>".
                        "<td class='camt' colspan='2'>₱ ".number_format($r['rel_allowance'],2,".", ",")."</td>".
                        // "<td colspan='3' class='erdc'></td>". 
                    "</tr> 
                    <tr>".
                        "<td>Other Allowance</td>".
                        "<td class='camt' colspan='2'>₱ ".number_format($others,2,".", ",")."</td>".
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


               
   } while($r = $cmd->fetch(PDO::FETCH_ASSOC));

     // $html ="</tbody>";
     //       $html = "<tfoot></tfoot>";    

}else { 

    $html .= '<tfoot><tr><td colspan="11" class="cnt"><button id="showpay" value="notok" hidden></button>No Payslip Found</td></tr></tfoot>'; 
}

$html .="</table>"; 

$pdf->WriteHTMLCell(192,0,9,'',$html,0);

//output
$pdf->Output();









