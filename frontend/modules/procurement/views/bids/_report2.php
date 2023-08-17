<?php
$fin = "";
$x = 0;
//echo $model->purchase_request_referrence_no;
foreach ($prdetails as $pr) {
    $x++;
    $itemdescription = $pr["purchase_request_details_item_description"];
    $quantity = $pr["purchase_request_details_quantity"];
    $abcTotal = $pr["purchase_request_details_price"] * $pr["purchase_request_details_quantity"];
    //$price = $pr["purchase_request_details_price"];
    $price = "";
    $totalcost = 5000;
    if ($quantity > 1) {
        $unit = $pr["name_short"];
    } else {
        $unit = $pr["name_long"];
    }
    $append = '<tr>';
    $append = $append . '<td style="font-size:12px;">' . $x . "</td>";
    $append = $append . "<td style='padding-left: 5px;vertical-align: top;font-size:12px;'>" . $itemdescription . "</b><br><br><br></td>";
    $append = $append . '<td style="font-size:12px;">' . $quantity . " " . $unit . "</td>";
    $append = $append . '<td style="font-size:12px;">' . number_format($abcTotal, 2, ".", ",") . "</td>";
    $append = $append . "<td></td>";
    $append = $append . "<td></td>";
    $append = $append . "<td></td>";
    $append = $append . "<td></td>";
    //$append = $append . "<td>" . $totalcost . "</td>";
    $append = $append . "</tr>";
    $fin = $fin . $append;
}
?>

<div class="print-container">
    <table width="100%">
        <tbody>
            <tr style="height: 10.6667px;">
                <td style="width: 81.4103%; height: 10.6667px;">
                    <p>&nbsp;</p>
                </td>
                <td style="width: 10.5897%; height: 10.6667px;">
                    <table border="1" width="100%" style="border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <td>
                                    <p>
                                    <h6 style-P><strong>FASS-PUR F08</strong>&nbsp; Rev. 1/07-01-23</h6>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <table width="100%" style="border-collapse: collapse;" border="0">
        <tbody>
            <tr>
                <td style="text-align: center;font-family:Arial;font-size:14px;">Republic of
                    the Philippines</td>
            </tr>
            <tr>
                <td style="text-align: center;font-family:Arial;font-size:14px;">
                    <b>DEPARTMENT OF SCIENCE AND TECHNOLOGY</b>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;font-family:Arial;font-size:14px;">Regional
                    Office No. IX</td>
            </tr>
            <tr>
                <td style="text-align: center;font-family:Arial;font-size:14px;">Petit
                    Barracks, Zone IV, Zamboanga City</td>
            </tr>
            <tr>
                <td style="text-align: center;font-family:Arial;font-size:14px;">
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td style="text-align: center;font-family:Arial;font-size:14px;">
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td style="text-align: center;font-family:Arial;font-size:18px;">
                    <b>REQUEST FOR QUOTATION</b>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;font-family:Arial;font-size:12px;">
                    <b>Date:<u><?=' '.date("F j, Y")?></u></b>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;font-family:Arial;font-size:12px;">
                    <b>RFQ No.:<u><?=' '.$rfqnumber?></u></b>
                </td>
            </tr>
        </tbody>
    </table>

    <table width="100%" style="border-collapse: collapse;" border="1">
        <tbody>
            <tr>
                <td style="text-align: left;font-family:Arial;font-size:12px;width:280px;border-top:none;border-bottom:none;border-right:none;border-left:none">
                    <p>&nbsp;</p>
                </td>
                <td style="text-align: center;font-family:Arial;font-size:12px;border-top:none;border-bottom:none;border-right:none;border-left:none">
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td style="text-align: left;font-family:Arial;font-size:12px;width:225px;border-top:none;border-bottom:none;border-right:none;border-left:none">Company Name/Business Name:</td>
                <td style="text-align: center;font-family:Arial;font-size:12px;border-top:none;border-bottom:1px solid;border-right:none;border-left:none"><?=$supplier ?></td>
            </tr>
            <tr>
                <td style="text-align: left;font-family:Arial;font-size:12px;width:225px;border-top:none;border-bottom:none;border-right:none;border-left:none">Address:</td>
                <td style="text-align: center;font-family:Arial;font-size:12px;border-top:none;border-bottom:1px solid;border-right:none;border-left:none"><?=$address ?></td>
            </tr>
            <tr>
                <td style="text-align: left;font-family:Arial;font-size:12px;width:225px;border-top:none;border-bottom:none;border-right:none;border-left:none">Business/Mayor\'s Permit No</td>
                <td style="text-align: center;font-family:Arial;font-size:12px;border-top:none;border-bottom:1px solid;border-right:none;border-left:none"></td>
            </tr>
            <tr>
                <td style="text-align: left;font-family:Arial;font-size:12px;width:225px;border-top:none;border-bottom:none;border-right:none;border-left:none">Company Tax Identification Number (TIN):</td>
                <td style="text-align: center;font-family:Arial;font-size:12px;border-top:none;border-bottom:1px solid;border-right:none;border-left:none"></td>
            </tr>
            <tr>
                <td style="text-align: left;font-family:Arial;font-size:12px;width:225px;border-top:none;border-bottom:none;border-right:none;border-left:none">PhilGEPS Registration Number:</td>
                <td style="text-align: center;font-family:Arial;font-size:12px;border-top:none;border-bottom:1px solid;border-right:none;border-left:none"></td>
            </tr>
        </tbody>
    </table>
    <div>&nbsp;</div>
    <div style="border:1px solid black;">
        <p style="padding-left: 10px; font-family:Arial; font-size:10px;"><b><i>TO WHOM IT MAY CONCERN:</i></b></p>
        <p style="padding-left: 10px; font-family:Arial; font-size:10px;">
            <i>Please quote your best offer with lowest price/s on the lot or item/s below, subject to the General Conditions indicated herein,
                stating the shortest time of delivery and submit your quotation duly signed by your official representative not later than
                _____________________ at _______ to the address listed above.</i>
        </p>
        <p>&nbsp;</p>
        <p style="padding-left: 450px; font-family:Arial; font-size:10px;"><i>Very truly yours,</i><br><br><b>RONNEL B. GUNDOY</b><br><b>Supply Officer</b></p>
    </div>
    <div>&nbsp;</div>
    <div style="border:1px solid black; font-family:Arial; font-size:10px;">
        <p style="text-align: center;"><b><u>GENERALCONDITIONS<u></b></p>
        <ol>
            <li>Bidders shall provide correct and accurate information required in this form.</li>
            <li>Any interlineations, erasures, or overwriting shall be valid only if they are signed or initialed by you or any of your duly authorized representative/s.</li>
            <li>All entries must be typewritten or must be legible if handwritten;</li>
            <li>Price quotation/s must be valid for a period of ninety (90) calendar days from the deadline of submission.</li>
            <li>Price quotation/s, to be denominated in Philippine peso, shall include all taxes, duties, and/or levies payable.</li>
            <li>Quotations exceeding the Approved Budget for the Contract (ABC) shall be rejected.</li>
            <li>Award of contract shall be made to the lowest quotation which complies with the technical specifications, requirements and other terms and conditions stated herein.</li>
            <li>The item/s shall be delivered according to the accepted offer of the bidder.</li>
            <li>Item/s delivered shall be inspected on the scheduled date and time of DOST-IX. The delivery of the item/s shall be acknowledged upon the delivery to confirm the compliance with the technical specifications.</li>
            <li>Item/s delivered must have warranties for unit replacements, parts, labor or other services as applicable.</li>
            <li>Payment shall be made after delivery and upon the submission of the required supporting documents, i.e., Order Slip and/or Billing statement, Charge/Sales Invoice by the supplier, contractor, or consultant. Our Government Servicing Bank, i.e., the Land Bank of the Philippines (LBP), shall credit the amount due to the LBP account of the supplier, contractor, or consultant not earlier than twenty-four (24) hours, but not later than forty-eight (48) hours, upon receipt of our advice. </li>
            <li>Liquidated damages equivalent to one-tenth of one percent (0.1%) of the value of the goods not delivered within the prescribed delivery period shall be imposed per day of delay. DOST-IX may terminate the contract once the cumulative amount of liquidated damages reaches ten percent (10%) of the amount of the contract, without prejudice to other courses of action and remedies open to it.</li>
        </ol>
    </div>

    <pagebreak>

        <p><b>After having carefully read and accepted Your General Conditions, I/We submit our quotation/s for the item/s as follows:</b></p>

        <table style="overflow: wrap;border-collapse: collapse;" autosize="1" width="100%" border="1">
            <tbody>
                <tr>
                    <td style="text-align: center;font-family:Arial;font-size:12px; width:30px" rowspan="3">No.</td>
                    <td style="text-align: center;font-family:Arial;font-size:12px; width:180px" rowspan="3">Item Description</td>
                    <td style="text-align: center;font-family:Arial;font-size:12px;" rowspan="2">Quantity</td>
                    <td style="text-align: center;font-family:Arial;font-size:12px; width:65px" rowspan="3">ABC</td>
                    <td style="text-align: center;font-family:Arial;font-size:12px; background-color:dimgray; color:white;" colspan="4">TO BE FILLED UP BY SUPPLIER/CONTRACTOR/CONSULTANT</td>
                </tr>
                <tr>
                    <td style="text-align: center;font-family:Arial;font-size:12px;" colspan="2">Financial Proposal<br>(Please indicate your price offer)</td>
                    <td style="text-align: center;font-family:Arial;font-size:12px;" rowspan="2">Technical Proposal<br>(Please indicate Brand/Model<br>offer, if applicable)</td>
                    <td style="text-align: center;font-family:Arial;font-size:12px;" rowspan="2">Delivery Term</td>
                </tr>
                <tr>
                    <td style="text-align: center;font-family:Arial;font-size:12px;">Unit</td>
                    <td style="text-align: center;font-family:Arial;font-size:12px;">Unit Price</td>
                    <td style="text-align: center;font-family:Arial;font-size:12px;">Total Price</td>
                </tr>
                <?php
                echo $fin;
                ?>
            </tbody>
        </table>
        <div>&nbsp;</div>
        <div style="border:1px solid black; font-family:Arial; font-size:10px;">
            <p style="text-align: left; padding-left:10px"><b><u>TERMS OF PAYMENT<u></b></p>
            <p style="text-align: left; padding-left:10px;"><b>Payment shall be made through Land Bank’s LDDAP-ADA facility, within thirty (30) days after Submission of Billing Statements or Charge/Sales Invoice and Inspection and User Acceptance of the products/services.</b></p>
            <p style="text-align: left; padding-left:10px; padding-top:10px"><b><u><i>Landbank (LBP) Account Details:</i><u></b></p>
            <p style="text-align: left; padding-left:10px">Account Number:&ensp;______________________________________________________________________________________________________________________________
                <br>Account Name:&ensp;&ensp;&ensp;&nbsp;______________________________________________________________________________________________________________________________
            </p>
            <p style="text-align: left; padding-left:10px; padding-top:10px;">In case of unavailability of LBP Account, payments shall be made through check payable to the registered company/business name. Check may be sent through courier at the registered company/business address or may be claimed at the DOST-IX Office, Zamboanga City.</p>
        </div>
        <div>
            <p style="padding-left:50px; padding-top:15px;">I hereby certify that the above information is true and correct.</p>
            <p style="padding-left:50px; padding-top:15px;">_______________________________________________________________<br>Signature over Printed Name of Authorized Representative</p>
            <p style="padding-left:50px; padding-top:15px;">_______________________________________________________________<br>Position/Designation</p>
            <p style="padding-left:50px; padding-top:15px;">_______________________________________________________________<br>Contact Nos.</p>
        </div>
</div>