--USE [new_sinkalmarlarnd]
GO
/****** Object:  StoredProcedure [dbo].[sp_pettycash]    Script Date: 2/6/2015 10:49:35 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[sp_pettycash]
@type varchar(1),
@claim_no varchar(20),
@claim_date datetime,
@petty_desc varchar(100),
@acc_no varchar(25),
@amount numeric(18, 2),
@amount2 numeric(18, 2),
@saldo2 numeric(18, 2)

--sp_pettycash 2, 'PC/2015/1/00066', '2015-01-05', 'Test' , '5010.1341.1', 200000, 0, 2500000

AS

--select*from db_pettyclaim
	DECLARE @saldo numeric(18,2), @saldo3 numeric(18,2), @total int, @claim_no2 varchar(20),@jumlah0 int,@cek_last_opening_close int, 
			@status_is_open_closing int, @sub_claim_no varchar(20), @acc_name varchar(200),@status_is_open int, @cek_id int, @a int, @b int, @ID int
	
	--SET @saldo3 = isnull(@saldo2,0)
	
	select @acc_name=acc_name from db_coa where acc_no=@acc_no
	
	select @sub_claim_no=claim_no from db_pettyclaim where Type = 1 and status = 1 

	SELECT @saldo=isnull(sum(saldo),0) from db_pettyclaim where pettycash_id =(select max(pettycash_id) from db_pettyclaim ) 
	
	select @total=count(pettycash_id) from db_pettyclaim

	select @jumlah0=count(status) from db_pettyclaim where status=0
	
	select @cek_last_opening_close=MAX(pettycash_id) from db_pettyclaim where status in(1,2)

	select @status_is_open_closing=ISNULL(MAX(pettycash_id), 0) from db_pettyclaim where pettycash_id > @cek_last_opening_close and status <> 2

	SELECT @ID=pettycash_id from db_pettyclaim where claim_no=@claim_no

	select @status_is_open=count(pettycash_id) from db_pettyclaim where status not in (0,2) and pettycash_id < @ID

	SET @a = @ID

	SET @a = @a - 1

	SET @b = 0

	WHILE @b = 0
	BEGIN
		select @cek_id=count(pettycash_id) from db_pettyclaim where pettycash_id = @a and status != 2
		IF @cek_id = 1
			BEGIN
				SET @b = 1
				BREAK
			END
		ELSE
			SET @a = @a - 1
			CONTINUE
	END
	
	IF @total=0
		BEGIN
			SET @saldo3=0
		END
	ELSE
	BEGIN
	
		IF @jumlah0=0
			BEGIN

				SELECT     @saldo3=isnull(saldo,0) 
				FROM         dbo.db_pettyclaim
				WHERE     (status = 2) AND (pettycash_id =
										  (SELECT     MAX(pettycash_id) AS Expr1
											FROM          dbo.db_pettyclaim AS db_pettyclaim_1
											WHERE      (status = 2)))
			END
		ELSE
			BEGIN
				IF @status_is_open_closing = 0
					BEGIN
						SELECT @saldo3=isnull(saldo,0)
								FROM dbo.db_pettyclaim
								WHERE (status in (1, 2)) AND (pettycash_id =
														  (SELECT     MAX(pettycash_id) AS Expr1
															FROM          dbo.db_pettyclaim AS db_pettyclaim_1
															WHERE      (status in (1, 2))))
					END
				ELSE
					BEGIN
						IF @type = 1
							BEGIN
								SELECT     @saldo3=isnull(saldo,0) 
								FROM         dbo.db_pettyclaim
								WHERE     (status in (1, 2)) AND (pettycash_id =
														  (SELECT     MAX(pettycash_id) AS Expr1
															FROM          dbo.db_pettyclaim AS db_pettyclaim_1
															WHERE      (status in (1, 2))))
							END
						ELSE
							BEGIN
								SELECT     @saldo3=isnull(saldo,0) 
								FROM         dbo.db_pettyclaim
								WHERE     (status <> 2) AND (pettycash_id = @a)
							END
				
					END

			END 
	END
    
    SELECT @claim_no2=count(claim_no) from db_pettyclaim where claim_no=@claim_no
    
    IF @claim_no2=1 and @type=1
		BEGIN
    
			UPDATE db_pettyclaim set claim_date=@claim_date, [type]=@type, acc_no=@acc_no,  debet=@amount, petty_desc=@petty_desc
			where claim_no=@claim_no
			UPDATE db_pettyclaim set saldo=debet where claim_no=@claim_no

		END 
    ELSE
			
			DECLARE @jml_edit int, @hit_jml_edit int, @amount_edit numeric
			
			IF @claim_no2=1 and @type=2

				BEGIN

					select @jml_edit=count(pettycash_id) from db_pettyclaim where pettycash_id >= @ID

					IF @jml_edit = 1
						BEGIN

							UPDATE db_pettyclaim set claim_date=@claim_date, [type]=@type, acc_no=@acc_no,  credit=@amount, petty_desc=@petty_desc
							where claim_no=@claim_no
							UPDATE db_pettyclaim set saldo=(@saldo2-@amount)
							where claim_no=@claim_no

						END
					ELSE IF @jml_edit > 1 

						BEGIN

							IF @amount < @amount2
								BEGIN
									SET @amount_edit = @amount2 - @amount

									UPDATE db_pettyclaim set claim_date=@claim_date, [type]=@type, acc_no=@acc_no,  credit=@amount, petty_desc=@petty_desc
									where claim_no=@claim_no
									UPDATE db_pettyclaim set saldo=(saldo+@amount_edit) where claim_no=@claim_no

									UPDATE db_pettyclaim set saldo=(saldo+@amount_edit) where pettycash_id > @ID

								END
							ELSE 
								BEGIN

									SET @amount_edit = @amount - @amount2

									UPDATE db_pettyclaim set claim_date=@claim_date, [type]=@type, acc_no=@acc_no,  credit=@amount, petty_desc=@petty_desc
									where claim_no=@claim_no
									UPDATE db_pettyclaim set saldo=(saldo-@amount_edit) where claim_no=@claim_no

									UPDATE db_pettyclaim set saldo=(saldo-@amount_edit) where pettycash_id > @ID

								END

						END

					
				END

			ELSE
					IF @claim_no2=0
						BEGIN
							IF @type='1'
								BEGIN
									INSERT INTO DB_PETTYCLAIM ([Type], claim_no, acc_no, acc_name, claim_date, saldo, rate, debet,
									credit, petty_desc, status, user_, datetime, status_reimburse, sub_claim_no)
									VALUES (@type, @claim_no, '', '', @claim_date, 0, 1, @amount, 0, @petty_desc, 1, 'MGR',
									getdate(), 0,@claim_no)
									UPDATE DB_PETTYCLAIM SET saldo=(@saldo3+debet)-credit where claim_no=@claim_no
									--UPDATE DB_PETTYCLAIM SET saldo=(@saldo2) where claim_no=@claim_no
								END
							ELSE
								BEGIN
									INSERT INTO DB_PETTYCLAIM ([Type], claim_no, acc_no, acc_name, claim_date, saldo, rate, debet,
									credit, petty_desc, status, user_, datetime, status_reimburse, sub_claim_no)
									VALUES (@type, @claim_no, @acc_no, @acc_name, @claim_date, 0, 1, 0, @amount, @petty_desc, 0, 'MGR',
									getdate(), 0, @sub_claim_no)
	
									UPDATE DB_PETTYCLAIM SET saldo=(@saldo+debet)-credit where claim_no=@claim_no
									--UPDATE DB_PETTYCLAIM SET saldo=(@saldo2) where claim_no=@claim_no
								END
						END


