ALTER TABLE engine4_sesadvancedactivity_activitycomments ADD COLUMN IF NOT EXISTS gif_url TEXT NULL DEFAULT NULL;
ALTER TABLE engine4_sesadvancedactivity_corecomments ADD COLUMN IF NOT EXISTS gif_url TEXT NULL DEFAULT NULL;
ALTER TABLE engine4_sesadvancedactivity_details ADD COLUMN IF NOT EXISTS gif_url TEXT NULL DEFAULT NULL;
