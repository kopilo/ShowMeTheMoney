select * from transactions,keyword_tag where detail like CONCAT("%",keyword_tag.keyword,"%") AND
