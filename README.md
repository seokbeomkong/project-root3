# 진단 결과 데이터 관리 시스템 (ECOLI 데이터 관리 시스템)

## 1. 과제 내용
이 프로젝트는 실험실에서 배양한 대장균(ECOLI)의 정보를 관리하는 시스템입니다.  
대장균들은 일정 주기로 분화하며, 분화 시작한 개체를 부모 개체, 분화되어 나온 개체를 자식 개체라고 합니다.  
프로젝트는 다음과 같은 기능을 포함합니다:
- **데이터 입력/삽입:** 대장균 개체의 정보를 (ID, 부모 개체 ID, 개체 크기, 분화 날짜, 형질) 저장합니다.
- **데이터 조회 및 계산:** 각 분화 연도별로 최대 개체 크기를 기준으로, 각 개체의 크기 편차를 계산하여 출력합니다.
- **데이터 수정/삭제:** 필요에 따라 입력된 데이터를 수정하거나 삭제할 수 있습니다.

## 2. 설치 및 실행 방법 (XAMPP 기준)
### 필수 요구사항
- PHP 7 이상
- MySQL
- XAMPP (또는 Apache, MySQL이 포함된 통합 개발 환경)

### 설치 및 실행 절차
1. **프로젝트 다운로드**  
   - GitHub Repository에서 프로젝트를 클론하거나 압축 파일로 다운로드합니다.  
     예시:  
     ```
     git clone https://github.com/seokbeomkong/project-root3.git
     ```
   
2. **프로젝트 파일 배치**  
   - 다운로드한 프로젝트 폴더를 XAMPP의 웹 서버 문서 루트 (예: `C:\xampp\htdocs\jangseokbeom_project\project-root3\`)에 위치시킵니다.
   
3. **데이터베이스 설정**  
   - MySQL에 접속 (phpMyAdmin 또는 MySQL 클라이언트를 사용)한 후, 아래 쿼리를 실행하여 데이터베이스와 테이블을 생성합니다.
   
   ```sql
   CREATE DATABASE IF NOT EXISTS diagnosis_db;
   USE diagnosis_db;
   
   CREATE TABLE IF NOT EXISTS ECOLI_DATA (
       ID INTEGER NOT NULL,
       PARENT_ID INTEGER,
       SIZE_OF_COLONY INTEGER NOT NULL,
       DIFFERENTIATION_DATE DATE NOT NULL,
       GENOTYPE INTEGER NOT NULL
   );