ALTER TABLE `students` ADD `program_id` INT NOT NULL AFTER `id`;
ALTER TABLE `students` ADD `exam_result_sheet` TEXT NULL AFTER `exam_status`;