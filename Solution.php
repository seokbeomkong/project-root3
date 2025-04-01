<?php
// 데이터베이스 연결 정보 설정
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "diagnosis_db";

// MySQLi를 이용하여 데이터베이스 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 체크
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 1. ECOLI_DATA 테이블 생성 (이미 존재하면 생성하지 않음)
$createTableSql = "
CREATE TABLE IF NOT EXISTS ECOLI_DATA (
    ID INTEGER NOT NULL,
    PARENT_ID INTEGER,
    SIZE_OF_COLONY INTEGER NOT NULL,
    DIFFERENTIATION_DATE DATE NOT NULL,
    GENOTYPE INTEGER NOT NULL
);";

if (!$conn->query($createTableSql)) {
    die("테이블 생성 오류: " . $conn->error);
}

// 2. 예시 데이터 삽입
// 이미 데이터가 있는 경우 중복 삽입을 피하기 위해 간단하게 삭제 후 삽입하거나, 조건을 추가할 수 있습니다.
$deleteExisting = "DELETE FROM ECOLI_DATA;";
$conn->query($deleteExisting);

$insertSql = "
INSERT INTO ECOLI_DATA (ID, PARENT_ID, SIZE_OF_COLONY, DIFFERENTIATION_DATE, GENOTYPE)
VALUES
  (1, NULL, 10, '2019-01-01', 0),   -- 2019년, 크기 10 → 편차: 10-10=0
  (2, 1, 2, '2019-06-01', 0),       -- 2019년, 크기 2  → 편차: 10-2=8
  (3, 1, 100, '2020-03-01', 0),     -- 2020년, 크기 100 → 편차: 100-100=0
  (4, 3, 10, '2020-04-01', 0),      -- 2020년, 크기 10  → 편차: 100-10=90
  (5, 3, 17, '2020-05-01', 0),      -- 2020년, 크기 17  → 편차: 100-17=83
  (6, 2, 101, '2021-01-01', 0);     -- 2021년, 크기 101 → 편차: 101-101=0
";

if (!$conn->query($insertSql)) {
    echo "데이터 삽입 오류: " . $conn->error;
}

// 3. 분화된 연도별 대장균 크기의 편차 계산 및 결과 출력
$selectSql = "
SELECT
    YEAR(e.DIFFERENTIATION_DATE) AS YEAR,
    (t.max_size - e.SIZE_OF_COLONY) AS YEAR_DEV,
    e.ID
FROM ECOLI_DATA e
JOIN (
    SELECT 
        YEAR(DIFFERENTIATION_DATE) AS diff_year, 
        MAX(SIZE_OF_COLONY) AS max_size
    FROM ECOLI_DATA
    GROUP BY YEAR(DIFFERENTIATION_DATE)
) t ON YEAR(e.DIFFERENTIATION_DATE) = t.diff_year
ORDER BY YEAR ASC, YEAR_DEV ASC;
";

$result = $conn->query($selectSql);

echo "<h3>ECOLI_DATA 결과</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>분화 연도</th><th>대장균 크기 편차 (YEAR_DEV)</th><th>ID</th></tr>";

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        echo "<tr>";
        echo "<td>" . $row["YEAR"] . "</td>";
        echo "<td>" . $row["YEAR_DEV"] . "</td>";
        echo "<td>" . $row["ID"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>결과가 없습니다.</td></tr>";
}

echo "</table>";

$conn->close();
?>
