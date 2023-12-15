<?php 
					$myDB = new MysqliDb();
					$dataCO = $myDB->query('call sp_getComboCount("'.$_SESSION['__user_logid'].'")');
					if(count($dataCO) > 0 && $dataCO)
					{
						echo '<span><span class="pull-right" style="margin-left: 10px;">CO <span>'.$dataCO[0]['CO'].'</span></span>';
					}
					else
					{
						echo '<span><span class="pull-right" style="margin-left: 10px;">CO <span>0</span></span>';
					}
					$myDB = new MysqliDb();
					$dataPL = $myDB->query('call get_paidleave_current(curdate(), "'.$_SESSION['__user_logid'].'");');
					$pl = 0;
					if(count($dataPL) > 0 && $dataPL)
					{
						if(count($dataPL) > 0)
						{
							$myDB = new MysqliDb();
							$dataPL1 = $myDB->query('call get_paidleave_urned(curdate(), "'.$_SESSION['__user_logid'].'");');															if(isset($dataPL[0]['paidleave']))
							{
								
									$pl = $dataPL[0]['paidleave'] - $dataPL1[0]['paidleave'];
							}
							
						}
						echo '<span class="pull-right">PL <span>'.$pl.'</span></span></span>';
					}
					else
					{
						echo '<span class="pull-right">PL <span>0</span></span></span>';
					}
				
				?>