create or replace view transaction_tags as (select id,dateCleared,detail,amount,tag from transactions,keyword_tag where detail like CONCAT('%',keyword_tag.keyword,'%'));
/*select untagged transactions */
select * from transactions a 
NATURAL LEFT JOIN transaction_tags b
WHERE b.id is null