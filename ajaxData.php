<?php 
include_once 'db.php';

if(!empty($_POST["year"])){ 
    $query = "SELECT DISTINCT 분기 FROM newTotalUploadNomissing WHERE 연도 = '".$_POST['year']."' ORDER BY 분기 ASC";
    echo $query;
    $result = $conn->query($query);

    // Generate HTML of state options list 
    if($result->num_rows > 0){ 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['분기'].'">'.$row['분기'].'</option>'; 
        } 
    }else{ 
        echo '<option value="">분기 선택이 불가능합니다</option>'; 
    }
} 

if(!empty($_POST["region"])){ 
    $query = "SELECT DISTINCT 기관 FROM newTotalUploadNomissing WHERE 권역 = '".$_POST['region']."' ORDER BY 기관 ASC";
    $result = $conn->query($query);

    // Generate HTML of state options list 
    if($result->num_rows > 0){ 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['기관'].'">'.$row['기관'].'</option>'; 
        } 
    }else{ 
        echo '<option value="">기관 선택이 불가능합니다</option>'; 
    }
} 
?>
