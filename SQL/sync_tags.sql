/*INSERT INTO TRANSACTIONS(tag),
SELECT (keyword,tag) FROM
keyword_tag*/

select * from transactions,keyword_tag where detail like CONCAT("%",keyword_tag.keyword,"%");
