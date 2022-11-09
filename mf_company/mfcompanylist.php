<?php

Class MfcompanyList{

    public function GetAllMfcompanyList(){
        global $connL;

        echo '<table id="allMfcompanyList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Company Code</th>
                <th>Company Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT * from dbo.mf_company ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();


        if($result){
            do { 
                $rowd = "'".$result['rowid']."'";
                $cde = "'".$result['code']."'";
                echo '
                <tr>
                <td id="cc'.$result['rowid'].'">' . $result['code']. '</td>
                <td id="cn'.$result['rowid'].'">' . $result['descs']. '</td>
                <td id="st'.$result['rowid'].'">' . $result['status']. '</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm" 
                onclick="editMfcompanyModal('.$rowd.','.$cde.')">
                                <i class="fas fa-edit"></i> Update
                            </button></td>';
                
            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot></tfoot>'; 
        }


        echo '</table>';
    }


}

?>

