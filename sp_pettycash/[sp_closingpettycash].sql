--USE [new_sinkalmarlarnd]
GO
/****** Object:  StoredProcedure [dbo].[sp_closingpettycash]    Script Date: 2/6/2015 10:50:53 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



--sp_approveap 'IS/2014/12/000009'

--select*from db_glheader where module='PC'

--select*from db_gldetail where voucher='PC/2015/1/00089     '

--[sp_closingpettycash] 212

CREATE PROCEDURE [dbo].[sp_closingpettycash]
@petty_id int
AS

--select*from db_subproject where id_pt = 22

BEGIN
	
	declare @doc_no varchar(20), @petty_date datetime, @petty_desc varchar (100), @debet numeric(18,2)

	select @doc_no=claim_no, @petty_date=claim_date, @petty_desc=petty_desc from db_pettyclaim where pettycash_id=@petty_id


	INSERT INTO DB_GLHEADER (project_cd, voucher, 
	trans_date, [desc], debit, credit, balance, module, ref_no, status,audit_user,
	audit_date, entry_date)
	VALUES('11', @doc_no, @petty_date, 'REIMBURS'+' '+@petty_desc, 0, 0, 0, 'PC', '', 0,22,getdate(),getdate())
	
	
	
	DECLARE @doc_no2 varchar(20),@acc_no5 varchar(30), @descs2 varchar(250), @banyak int, @looping int, @jumlah int,
	@debet2 numeric(18,2),@credit2 numeric(18,2),@acc_name5 varchar(100), @id_other int

	select ROW_NUMBER() OVER (ORDER BY db_pettyclaim.claim_no) AS Row, * into #1 from db_pettyclaim WHERE Type != 1 AND sub_claim_no=@doc_no

	SELECT @jumlah=COUNT(row) FROM #1 
	SELECT @id_other=MIN(row) FROM #1 

	set @Looping = 1

	SELECT @Banyak=@jumlah
	
	while (@Looping <= @Banyak)
	BEGIN


	SELECT @acc_no5=acc_no, @descs2=petty_desc, @credit2=credit
	from #1 where row=@id_other 
	
	
	
	INSERT INTO DB_GLDETAIL (dept,voucher,acc_no,acc_curr,acc_name,line_desc,
	debit,credit,rate_forex,base_amount,module,trans_date,audit_user,audit_date,project_no)
	VALUES('HRD',@doc_no,@acc_no5,'1','',@descs2,@credit2,0,1,@credit2,'PC',getdate(),22,getdate(),'11')
	
	set @Looping = @Looping + 1
	set @id_other = @id_other + 1
	
	END
	
	
	
END

	select @debet=sum(debit)from db_gldetail where voucher=@doc_no

	INSERT INTO DB_GLDETAIL (dept,voucher,acc_no,acc_curr,acc_name,line_desc,
	debit,credit,rate_forex,base_amount,module,trans_date,audit_user,audit_date,project_no)
	VALUES('HRD',@doc_no,'1110.2602.1','1','Bukopin Opr THE WAVE (1001606421)',@petty_desc,0,@debet,1,@debet,'PC',getdate(),22,getdate(),'11')
	
	





























