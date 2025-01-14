select
  user_comments.id,
  user_comments.author_id,
  user_comments.game_id,
  user_comments.parent_comment_id,
  unix_timestamp(user_comments.date) as `date`,
  user_comments.hidden,
  user_comments.content,
  user_accounts.fullname as author_fullname,
  games.name as game_name
from user_comments
  inner join user_accounts
    on user_comments.author_id = user_accounts.username
  inner join games
    on user_comments.game_id = games.id
order by user_comments.date asc
