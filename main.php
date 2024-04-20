<?php
/* connect to server */
$conn = mysqli_connect("localhost", "root", "", "lab9_dbs");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/* drop existing tables */
$drop1 = "drop table if exists team";
$drop2 = "drop table if exists students";
$drop3 = "drop table if exists team_students";

$stmt = $conn->prepare($drop1);
$stmt->execute();
$stmt = $conn->prepare($drop2);
$stmt->execute();
$stmt = $conn->prepare($drop3);
$stmt->execute();

/* create tables */
$sql1 = "create table team (team_id int, aguulga_id int, name varchar(50));";
$sql2 = "create table students (id int, name varchar(50), student_id char(11)); ";
$sql3 = "create table team_students (team_id int, student_no int)";

$stmt = $conn->prepare($sql1);
$stmt->execute();
$stmt = $conn->prepare($sql2);
$stmt->execute();
$stmt = $conn->prepare($sql3);
$stmt->execute();

/* insert data */
$sql_data1 = "insert into team (team_id, aguulga_id, name) values
                (1,1,'Recovery algorithm'),
                (2,2,'Buffer management'),
                (3,3,'Failure with loss of non-volatile storage'),
                (4,4,'High availability using remote backup systems'),
                (5,5,'Early lock release and logical undo operations'),
                (6,6,'ARIES'),
                (7,7,'Recovery In Main-Memory Databases');";

$sql_data2 = "insert into students (id, name, student_id) values
                ('1', 'Давгаренчин.Бая', '19B1NUM0177'),
                ('2','Энхтайван.Шүр', '22B1NUM3410'),
                ('3','Сугарсүрэн.Мөн', '21B1NUM1818'),
                ('4','Билгүүн.Бат', '21B1NUM0269'),
                ('5','Мөнгөншагай.Бол', '22B1NUM7873'),
                ('6','Төгөлдөр.Энх', '22B1NUM7303'),
                ('7','Оч-Уянга.Сам', '22B1NUM0180'),
                ('8','Дарьбазар.Чул', '22B1NUM5504'),
                ('9','Нямбаяр.Очи', '22B1NUM5284'),
                ('10','Танан-Эрдэнэ.Эрд', '21b1num0561'),
                ('11','Өсөхбаяр.Бат', '21B1NUM0824'),
                ('12','Мөнгөнцэцэг.Төм', '21B1NUM2278'),
                ('13','Мөнх-Оргил.Бая', '22B1NUM1063'),
                ('14','Жаргалмаа.Даш', '19B1NUM1046'),
                ('15','Жавхлан.Ган', '22B1NUM5577'),
                ('16','Насан-Очир.Мөн', '22B1NUM6572'),
                ('17','Намхай.Ган', '21B1NUM0908'),
                ('18','Намуулин.Туя', '22B1NUM4522'),
                ('19','Энхцэцэг.Энх', '20B1NUM0066'),
                ('20','Машбат.Бал', '21B1NUM0791'),
                ('21','Очирбаяр.Айм', '21B1NUM1291'),
                ('22','Маргад-Эрдэнэ.Бая', '20b1num1825'),
                ('23','Эрдэнэ.Энх', '22B1NUM7309'),
                ('24','Шинэбаяр.Алт', '20B1NUM0705'),
                ('25','Уянга.Түм', '22B1NUM6701');";

$stmt = $conn->prepare($sql_data1);
$stmt->execute();
$stmt = $conn->prepare($sql_data2);
$stmt->execute();

/* Create an array with numbers from 1 to 25 */
$numbers = range(1, 25);
$team = array(1,1,1,1,2,2,2,2,3,3,3,3,4,4,4,4,5,5,5,6,6,6,7,7,7);

/* Shuffle the array to randomize the order */
shuffle($numbers);

/* randomly assign students to teams */
$i = 0;

while ($i < count($numbers)) {
    $student_id = $numbers[$i];
    $team_no=$team[$i];
    $sql = "insert into team_students (team_id, student_no) values ($team_no, $student_id)";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $i++;
}

/* execute query to display the result */
$query = "select t.team_id as team_no, t.name as aguulga, st.name as student_name, st.student_id as student_id
          from team_students ts
          left join team t on ts.team_id = t.team_id
          left join students st on st.id = ts.student_no
          order by team_no, student_name;";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Team no: " . $row["team_no"]. " - Aguulga: " . $row["aguulga"]. " - Student name: " . $row["student_name"] . " - Student id: " . $row["student_id"] . "<br>";
    }
} else {
    echo "0 results";
}


/* close connection */
$conn->close();
?>
