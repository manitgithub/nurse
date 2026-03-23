-- SQL script to import budget 2567
-- This script matches the logic in m260318_092801_import_budget_data_2567.php

-- 1. Insert Allocation for Category 1
INSERT INTO budget_allocations (category_id, fiscal_year, allocated_amount, adjustment_reduction, adjustment_addition) 
VALUES (1, '2567', 350000.00, 30000.00, 300000.00);
SET @allocation_id = LAST_INSERT_ID();
INSERT INTO budget_transactions (allocation_id, transaction_date, activity_name, proposed_amount, requester, note) 
VALUES (@allocation_id, '2024-12-31', 'สรุปยอดค่าใช้จ่ายประจำปี 2567 (นำเข้าจากระบบเดิม)', 549501.95, 'System', 'ข้อมูลนำเข้าอัตโนมัติจากสรุปยอดปี 2567');

-- 2. Insert Allocation for Category 2
INSERT INTO budget_allocations (category_id, fiscal_year, allocated_amount, adjustment_reduction, adjustment_addition) 
VALUES (2, '2567', 32000.00, 0.00, 0.00);
SET @allocation_id = LAST_INSERT_ID();
INSERT INTO budget_transactions (allocation_id, transaction_date, activity_name, proposed_amount, requester, note) 
VALUES (@allocation_id, '2024-12-31', 'สรุปยอดค่าใช้จ่ายประจำปี 2567 (นำเข้าจากระบบเดิม)', 31999.90, 'System', 'ข้อมูลนำเข้าอัตโนมัติจากสรุปยอดปี 2567');

-- 3. Insert Allocation for Category 3
INSERT INTO budget_allocations (category_id, fiscal_year, allocated_amount, adjustment_reduction, adjustment_addition) 
VALUES (3, '2567', 3650000.00, 1450000.00, 0.00);
SET @allocation_id = LAST_INSERT_ID();
INSERT INTO budget_transactions (allocation_id, transaction_date, activity_name, proposed_amount, requester, note) 
VALUES (@allocation_id, '2024-12-31', 'สรุปยอดค่าใช้จ่ายประจำปี 2567 (นำเข้าจากระบบเดิม)', 2142941.35, 'System', 'ข้อมูลนำเข้าอัตโนมัติจากสรุปยอดปี 2567');

-- 4. Insert Allocation for Category 4
INSERT INTO budget_allocations (category_id, fiscal_year, allocated_amount, adjustment_reduction, adjustment_addition) 
VALUES (4, '2567', 250000.00, 50000.00, 0.00);
SET @allocation_id = LAST_INSERT_ID();
INSERT INTO budget_transactions (allocation_id, transaction_date, activity_name, proposed_amount, requester, note) 
VALUES (@allocation_id, '2024-12-31', 'สรุปยอดค่าใช้จ่ายประจำปี 2567 (นำเข้าจากระบบเดิม)', 184110.00, 'System', 'ข้อมูลนำเข้าอัตโนมัติจากสรุปยอดปี 2567');

-- 5. Insert Allocation for Category 5
INSERT INTO budget_allocations (category_id, fiscal_year, allocated_amount, adjustment_reduction, adjustment_addition) 
VALUES (5, '2567', 87000.00, 0.00, 0.00);
SET @allocation_id = LAST_INSERT_ID();
INSERT INTO budget_transactions (allocation_id, transaction_date, activity_name, proposed_amount, requester, note) 
VALUES (@allocation_id, '2024-12-31', 'สรุปยอดค่าใช้จ่ายประจำปี 2567 (นำเข้าจากระบบเดิม)', 86999.40, 'System', 'ข้อมูลนำเข้าอัตโนมัติจากสรุปยอดปี 2567');

-- 6. Insert Allocation for Category 6
INSERT INTO budget_allocations (category_id, fiscal_year, allocated_amount, adjustment_reduction, adjustment_addition) 
VALUES (6, '2567', 50000.00, 0.00, 0.00);
SET @allocation_id = LAST_INSERT_ID();
INSERT INTO budget_transactions (allocation_id, transaction_date, activity_name, proposed_amount, requester, note) 
VALUES (@allocation_id, '2024-12-31', 'สรุปยอดค่าใช้จ่ายประจำปี 2567 (นำเข้าจากระบบเดิม)', 49961.70, 'System', 'ข้อมูลนำเข้าอัตโนมัติจากสรุปยอดปี 2567');

-- 7. Insert Allocation for Category 7
INSERT INTO budget_allocations (category_id, fiscal_year, allocated_amount, adjustment_reduction, adjustment_addition) 
VALUES (7, '2567', 10000.00, 0.00, 0.00);
SET @allocation_id = LAST_INSERT_ID();
INSERT INTO budget_transactions (allocation_id, transaction_date, activity_name, proposed_amount, requester, note) 
VALUES (@allocation_id, '2024-12-31', 'สรุปยอดค่าใช้จ่ายประจำปี 2567 (นำเข้าจากระบบเดิม)', 0.00, 'System', 'ข้อมูลนำเข้าอัตโนมัติจากสรุปยอดปี 2567');

-- 8. Insert Allocation for Category 8
INSERT INTO budget_allocations (category_id, fiscal_year, allocated_amount, adjustment_reduction, adjustment_addition) 
VALUES (8, '2567', 5000.00, 0.00, 0.00);
SET @allocation_id = LAST_INSERT_ID();
INSERT INTO budget_transactions (allocation_id, transaction_date, activity_name, proposed_amount, requester, note) 
VALUES (@allocation_id, '2024-12-31', 'สรุปยอดค่าใช้จ่ายประจำปี 2567 (นำเข้าจากระบบเดิม)', 4966.56, 'System', 'ข้อมูลนำเข้าอัตโนมัติจากสรุปยอดปี 2567');
