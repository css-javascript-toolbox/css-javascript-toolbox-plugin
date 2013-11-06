CREATE PROCEDURE `addPosts`(IN `postsCount` INT, IN `postType` VARCHAR(50), IN `startIndex` INT)
	LANGUAGE SQL
	NOT DETERMINISTIC
	CONTAINS SQL
	COMMENT ''
BEGIN
	-- Initialize.
	DECLARE postIndex INT DEFAULT 0;
	
	-- Create posts by the number specified in @postsCount parameter
	CREATE_ALL_POSTS: LOOP
		-- Add post
		INSERT INTO 60wp35_posts (post_author, post_content, post_title, post_excerpt, post_type) VALUES(1, "", CONCAT(postType, '--', (postIndex + startIndex + 1)), "", postType);
		-- Post added!
		SET postIndex = postIndex + 1;
		-- When the postIndex reached postsCount leave the loop as we're done!
		IF (postIndex = postsCount) THEN
			LEAVE CREATE_ALL_POSTS;
		END IF;
	END LOOP CREATE_ALL_POSTS;
END