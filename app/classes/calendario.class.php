<?php 
class Calendario extends Core {
		
	public $modulo;
	public $idsAvas;
	public $idsMatriculas;
	
	function gerarCalendario($idioma, $mes, $ano, $idsindicato = false) {
		echo '
		<div class="cdi">
			<div class="cdi-content">
				<div class="cdi-view cdi-grid">
					<table class="cdi-border-separate" cellspacing="0" style="width:100%">
						<thead>
							<tr class="cdi-first cdi-last">
								<th class="cdi-widget-header cdi-first" style="width: 127px;background-color:#CCCCCC;">'.$idioma["domingo"].'</th>
								<th class="cdi-widget-header" style="width: 127px;background-color:#CCCCCC;">'.$idioma["segunda"].'</th>
								<th class="cdi-widget-header" style="width: 127px;background-color:#CCCCCC;">'.$idioma["terca"].'</th>
								<th class="cdi-widget-header" style="width: 127px;background-color:#CCCCCC;">'.$idioma["quarta"].'</th>
								<th class="cdi-widget-header" style="width: 127px;background-color:#CCCCCC;">'.$idioma["quinta"].'</th>
								<th class="cdi-widget-header" style="width: 127px;background-color:#CCCCCC;">'.$idioma["sexta"].'</th>
								<th class="cdi-widget-header cdi-last" style="width: 127px;background-color:#CCCCCC;">'.$idioma["sabado"].'</th>
							</tr>
						</thead>
					<tbody>';

		$totalDias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano); //DIAS DO MES
		$primeiroDia = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mes, 1, $ano), 0); //PRIMEIRO DIA DO MES, SENDO 0 DOMINGO E 6 SABADO
		$ultimoDia = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mes, $totalDias, $ano), 0); //ULTIMO DIA DO MES, SENDO 0 DOMINGO E 6 SABADO


		//SE O PRIMEIRO DIA NÃO FOR NO DOMINGO CRIA LINHA COM DIAS DO MES ANTERIOR
		if($primeiroDia != 0) {
	
			$mesAnterior = $mes - 1;
			$anoMesAnterior = $ano;
			if($mesAnterior == 0) { $mesAnterior = 12; $anoMesAnterior--; } //PEGANDO ANO ANTERIOR SE MES ANTERIOR FOR MENOR QUE 1
	
			$totalDiasAnterior = cal_days_in_month(CAL_GREGORIAN, $mesAnterior, $anoMesAnterior);
			$ultimoDiaAnterior = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mesAnterior, $totalDiasAnterior, $anoMesAnterior), 0);
			$qtdDiasAnterior = ($totalDiasAnterior - $ultimoDiaAnterior);
	
			echo '<tr class="cdi-first">';
			$count = 0;
			for ($i = $qtdDiasAnterior; $i <= $totalDiasAnterior; $i++) {
				$count++;
				echo '<td class="cdi-widget-content cdi-first cdi-other-month ';
				if($count % 7 == 0) echo 'cdi-last';
				echo '"><div style="min-height: 108px;"><div class="cdi-day-number">'.$i.'</div></div></td>';
			}
		} else {
			echo '<tr class="cdi-first">';
		}

		//if($this->proximo) $this->dia = 1;
		for ($i = 1; $i <= $totalDias; $i++) {
			$diaSemana = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mes, $i, $ano), 0); //DIA DA SEMANA
	  
			echo '<td class="cdi-widget-content ';
			
			if($diaSemana == '6') echo 'cdi-last';
			
			echo '"><div style="min-height: 108px;"><div class="cdi-day-number">'.$i.'</div>';
			
			if($this->modulo == 'gestor' || ($this->modulo == 'aluno' && count($this->idsAvas) > 0)) {
				$this->sql = "select 
								count(*) as total 
							from 
								avas_chats 
							where
								(date_format(inicio_entrada_aluno,'%Y%m%d') <= '".$ano.str_pad($mes, 2, "0", STR_PAD_LEFT).str_pad($i, 2, "0", STR_PAD_LEFT )."' or inicio_entrada_aluno is null) and
								(date_format(fim_entrada_aluno,'%Y%m%d') >= '".$ano.str_pad($mes, 2, "0", STR_PAD_LEFT).str_pad($i, 2, "0", STR_PAD_LEFT )."' or fim_entrada_aluno is null) and
								ativo = 'S'";
				
				if($this->modulo == 'aluno') 
					$this->sql .= " and idava in (".implode(',',$this->idsAvas).")"; 
				
				$totalChats = $this->retornarLinha($this->sql);
				if($totalChats['total'] > 0) {
					$dia = $i;
					if($dia < 10) $dia = '0'.$dia;
					echo '<div class="cdi-day-content"><a class="label label-important" style="color:#FFFFFF;background-color:#006600;" href="/'.$this->url[0].'/'.$this->url[1].'/'.$this->url[2].'/chats/'.$ano.'-'.$mes.'-'.$dia.'" rel="facebox">'.$totalChats['total'].' '.$idioma['chats'].'</a></div>';
				}
			}
			
			if($this->modulo == 'gestor') {
				$this->sql = "select 
								count(distinct pp.id_prova_presencial) as total 
							from 
								provas_presenciais pp
								inner join provas_presenciais_escolas ppp on (pp.id_prova_presencial = ppp.id_prova_presencial and ppp.ativo = 'S')
								inner join escolas p on (ppp.idescola = p.idescola and p.ativo = 'S')
							where
								date_format(pp.data_realizacao,'%Y%m%d') = '".$ano.str_pad($mes, 2, "0", STR_PAD_LEFT).str_pad($i, 2, "0", STR_PAD_LEFT )."' and
								pp.ativo = 'S'";
				if($_SESSION["adm_gestor_sindicato"] <> "S")
					$this->sql .= " and p.idsindicato in (".$_SESSION["adm_sindicatos"].")";
				if($idsindicato)
					$this->sql .= " and p.idsindicato = ".$idsindicato;
					
				$totalProvas = $this->retornarLinha($this->sql);
				if($totalProvas['total'] > 0) {
					$dia = $i;
					if($dia < 10) $dia = '0'.$dia;
					echo '<div class="cdi-day-content"><a class="label label-important" style="color:#FFFFFF;background-color:#0033ff;" href="/'.$this->url[0].'/'.$this->url[1].'/'.$this->url[2].'/provas/'.$ano.'-'.$mes.'-'.$dia.'/'.$idsindicato.'" rel="facebox">'.$totalProvas['total'].' '.$idioma['provas'].'</a></div>';
				}
			} elseif($this->modulo == 'aluno' && count($this->idsMatriculas) > 0) {
				$this->sql = "select 
								count(*) as total 
							from 
								provas_solicitadas ps
								inner join provas_presenciais pp on (ps.id_prova_presencial = pp.id_prova_presencial and pp.ativo = 'S')
							where
								date_format(pp.data_realizacao,'%Y%m%d') = '".$ano.str_pad($mes, 2, "0", STR_PAD_LEFT).str_pad($i, 2, "0", STR_PAD_LEFT )."' and
								idmatricula in(".implode(',',$this->idsMatriculas).") and 
								ps.ativo = 'S'";
				$totalProvas = $this->retornarLinha($this->sql);
				if($totalProvas['total'] > 0) {
					$dia = $i;
					if($dia < 10) $dia = '0'.$dia;
					echo '<div class="cdi-day-content"><a class="label label-important" style="color:#FFFFFF;background-color:#0033ff;" href="/'.$this->url[0].'/'.$this->url[1].'/'.$this->url[2].'/provas/'.$ano.'-'.$mes.'-'.$dia.'" rel="facebox">'.$totalProvas['total'].' '.$idioma['provas'].'</a></div>';
				}
			}
			
			if($this->modulo == 'gestor') {
				$this->sql = "select 
								distinct f.nome 
							from 
								feriados f
								left outer join feriados_cidades fc on (f.idferiado = fc.idferiado and fc.ativo = 'S')
								left outer join feriados_estados fe on (f.idferiado = fe.idferiado  and fe.ativo = 'S')
								left outer join feriados_sindicatos fi on (f.idferiado = fi.idferiado and fi.ativo = 'S')
								left outer join feriados_escolas fp on (f.idferiado = fp.idferiado and fp.ativo = 'S')
								left outer join escolas p on (fp.idescola = p.idescola and p.ativo = 'S')
							where
								f.data = '".$ano.'-'.str_pad($mes, 2, "0", STR_PAD_LEFT).'-'.str_pad($i, 2, "0", STR_PAD_LEFT )."' and
								f.ativo = 'S'";
				
				if($_SESSION["adm_gestor_sindicato"] <> "S")
					$this->sql .= " and (
											(fi.idsindicato in (".$_SESSION["adm_sindicatos"].") or fi.idsindicato is null) and
											(p.idsindicato in (".$_SESSION["adm_sindicatos"].") or p.idsindicato is null) and
											(fc.idcidade in (select idcidade from sindicatos where idsindicato in (".$_SESSION["adm_sindicatos"].") and ativo = 'S') or fc.idcidade is null) and
											(fe.idestado in (select idestado from sindicatos where idsindicato in (".$_SESSION["adm_sindicatos"].") and ativo = 'S') or fe.idestado is null)
										)";
					
				if($idsindicato)
					$this->sql .= " and (
											(fi.idsindicato = ".$idsindicato." or fi.idsindicato is null) and 
											(p.idsindicato = ".$idsindicato." or p.idsindicato is null) and 
											(fc.idcidade in (select idcidade from sindicatos where idsindicato = ".$idsindicato." and ativo = 'S') or fc.idcidade is null) and
											(fe.idestado in (select idestado from sindicatos where idsindicato = ".$idsindicato." and ativo = 'S') or fe.idestado is null)
										)";
					
				$feriados = $this->retornarLinhas();
				foreach($feriados as $feriado) {
					$nomeFeriado = $feriado['nome'];
					if(strlen($nomeFeriado) > 20) {
						$nomeFeriado = substr($feriado['nome'],0,17).'...';
					}
					echo '<div class="cdi-day-content" rel="tooltip" data-original-title="'.$feriado['nome'].'"><span class="label label-important" style="color:#FFFFFF;background-color:#b94a48;">'.$nomeFeriado.'</span></div>';
				}
			} elseif($this->modulo == 'aluno' && count($this->idsMatriculas) > 0) {
				$this->sql = "select 
								distinct f.nome 
							from 
								feriados f
								left outer join feriados_cidades fc on (f.idferiado = fc.idferiado and fc.ativo = 'S')
								left outer join feriados_estados fe on (f.idferiado = fe.idferiado  and fe.ativo = 'S')
								left outer join feriados_sindicatos fi on (f.idferiado = fi.idferiado and fi.ativo = 'S')
								left outer join feriados_escolas fp on (f.idferiado = fp.idferiado and fp.ativo = 'S')
								left outer join matriculas m on ((fi.idsindicato = m.idsindicato or fi.idsindicato is null) and (fp.idescola = m.idescola or fp.idescola is null) and m.ativo = 'S')
								left outer join escolas p on (m.idescola = p.idescola and p.ativo = 'S')
							where
								f.data = '".$ano.'-'.str_pad($mes, 2, "0", STR_PAD_LEFT).'-'.str_pad($i, 2, "0", STR_PAD_LEFT )."' and
								m.idmatricula in(".implode(',',$this->idsMatriculas).") and
								f.ativo = 'S'";
				$feriados = $this->retornarLinhas();
				foreach($feriados as $feriado) {
					$nomeFeriado = $feriado['nome'];
					if(strlen($nomeFeriado) > 20) {
						$nomeFeriado = substr($feriado['nome'],0,17).'...';
					}
					echo '<div class="cdi-day-content" rel="tooltip" data-original-title="'.$feriado['nome'].'"><span class="label label-important" style="color:#FFFFFF;background-color:#b94a48;">'.$nomeFeriado.'</span></div>';
				}
			}
			
			echo '</div></td>';
	
			if($diaSemana == '6') echo '</tr>'; //SE SABADO FECHA A LINHA
			if($diaSemana == '6' && $i <= $totalDias){ //SE SABADO E EXITIR PROXIMA LINHA CRIA NOVA LINHA
				if($i == $totalDias - 6 || $i == $totalDias - 7 || $i >= 26) //SE ULTIMA SEMANA IMPRIMIRE CSS ULTIMO
					echo '<tr class="cdi-last">';
				else
					echo '<tr>';
				
			}
		}

		//SE ULTMO DIA NAO FOR SABADO ENTAO IMPRIME DIAS DO MES POSTERIOR
		if($ultimoDia != 6){
			$qtdDiasPosterior =  6 - $ultimoDia; //DIAS QUE FALTAM NO CALENDARIO
	
			for ($i = 1; $i <= $qtdDiasPosterior; $i++) {
				echo '<td class="cdi-widget-content cdi-first cdi-other-month ';
				if($i == $qtdDiasPosterior) echo 'cdi-last';
				echo '"><div style="min-height: 108px;"><div class="cdi-day-number">'.$i.'</div></div></td>';
			}
	
			echo '</tr>'; //FECHA A LINHA
		}
	
		echo '</tbody></table></div></div></div>';
	}
	
	function retornarChats($data) {
		$retorno = array();
		
		if($this->modulo == 'gestor' || ($this->modulo == 'aluno' && count($this->idsAvas) > 0)) {
			$this->sql = "select 
								ac.*,
								a.nome as ava
							from 
								avas_chats ac
								inner join avas a on (ac.idava = a.idava)
							where
								(date_format(ac.inicio_entrada_aluno,'%Y%m%d') <= '".str_replace('-','',$data)."' or ac.inicio_entrada_aluno is null) and
								(date_format(ac.fim_entrada_aluno,'%Y%m%d') >= '".str_replace('-','',$data)."' or ac.fim_entrada_aluno is null) and
								ac.ativo = 'S'";
			if($this->modulo == 'aluno') 
				$this->sql .= " and a.idava in (".implode(',',$this->idsAvas).")";
			
			$this->ordem_campo = 'ac.inicio_entrada_aluno asc, ac.idchat'; 
			$this->ordem = 'desc'; 
			$this->limite = -1; 
			
			$retorno = $this->retornarLinhas();
			if($this->modulo == 'aluno') {
				foreach($retorno as $ind => $val) {
					$matricula = $this->retornarMatriculaAvasAluno($this->idpessoa, $val['idava']);
					$retorno[$ind]['idmatricula'] = $matricula['idmatricula'];
					$retorno[$ind]['idbloco_disciplina'] = $matricula['idbloco_disciplina'];
				}
			}
		}
		
		return $retorno;
	}
	
	function retornarProvas($data, $idsindicato = false) {
		$this->sql = "select 
						*,
						(select count(*) from provas_solicitadas ppe where ppe.id_prova_presencial = pp.id_prova_presencial and ppe.situacao = 'E') as total_em_espera,
						(select count(*) from provas_solicitadas ppa where ppa.id_prova_presencial = pp.id_prova_presencial and ppa.situacao = 'A') as total_agendado,
						(select count(*) from provas_solicitadas ppc where ppc.id_prova_presencial = pp.id_prova_presencial and ppc.situacao = 'C') as total_cancelado
					from 
						provas_presenciais pp
						inner join provas_presenciais_escolas ppp on (pp.id_prova_presencial = ppp.id_prova_presencial and ppp.ativo = 'S')
						inner join escolas p on (ppp.idescola = p.idescola and p.ativo = 'S')
					where
						pp.data_realizacao = '".$data."' and
						pp.ativo = 'S'";
		if($_SESSION["adm_gestor_sindicato"] <> "S")
			$this->sql .= " and p.idsindicato in (".$_SESSION["adm_sindicatos"].")";
		if($idsindicato)
			$this->sql .= " and p.idsindicato = ".$idsindicato;
		
		$this->sql .= " group by pp.id_prova_presencial";
		
		$this->ordem_campo = 'pp.data_realizacao asc, pp.id_prova_presencial'; 
		$this->ordem = 'desc'; 
		$this->limite = -1; 
		
		return $this->retornarLinhas();
	}
	
	function retornarAvasAluno($idpessoa) {

		$retorno = array();
		
		$this->sql = "select 
						oca.idava
					from 
						matriculas m
						inner join ofertas_cursos_escolas ocp on (m.idoferta = ocp.idoferta and m.idcurso = ocp.idcurso and m.idescola = ocp.idescola and ocp.ativo = 'S')
						inner join ofertas_curriculos_avas oca on (ocp.idoferta = oca.idoferta and ocp.idcurriculo = oca.idcurriculo and oca.ativo = 'S')
					where
						m.idpessoa = ".$idpessoa." and
						m.ativo = 'S'
					group by oca.idava";
		$this->ordem_campo = 'oca.idava'; 
		$this->ordem = 'asc'; 
		$this->limite = -1; 
		
		$avas = $this->retornarLinhas();
		foreach($avas as $ava) {
			if ($ava['idava']) {
				$retorno[] = $ava['idava'];
			}
		}
		
		return $retorno;
    }
	
	function retornarMatriculaAvasAluno($idpessoa, $idava)
	{
		$this->sql = "SELECT 
							m.idmatricula,
							cbd.idbloco_disciplina
						FROM 
							matriculas m
							INNER JOIN ofertas_cursos_escolas ocp on (m.idoferta = ocp.idoferta and m.idcurso = ocp.idcurso and m.idescola = ocp.idescola and ocp.ativo = 'S')
							INNER JOIN ofertas_curriculos_avas oca on (oca.idoferta = m.idoferta and ocp.idcurriculo = oca.idcurriculo and oca.ativo = 'S')
							INNER JOIN curriculos_blocos cb ON (cb.idcurriculo = ocp.idcurriculo)
	                		INNER JOIN curriculos_blocos_disciplinas cbd ON (cbd.idbloco = cb.idbloco AND cbd.iddisciplina = oca.iddisciplina) 
						WHERE
							m.idpessoa = ".$idpessoa." and
							oca.idava = ".$idava." and
							m.ativo = 'S'
						GROUP BY m.idmatricula";
		$this->ordem_campo = 'm.idmatricula'; 
		$this->ordem = 'desc'; 
		$matricula = $this->retornarLinha($this->sql);
		
		return $matricula;
    }
	
	function retornarMatriculasAluno($idpessoa) {

		$retorno = array();
		
		$this->sql = "select 
						idmatricula
					from 
						matriculas
					where
						idpessoa = ".$idpessoa." and
						ativo = 'S'";
		$this->ordem_campo = 'idmatricula'; 
		$this->ordem = 'asc'; 
		$this->limite = -1; 
		
		$matriculas = $this->retornarLinhas();
		foreach($matriculas as $matricula) {
			$retorno[] = $matricula['idmatricula'];
		}
		
		return $retorno;
    }
	
	function retornarProvasAluno($data) {
		$this->sql = "select 
						pp.*,
						ps.situacao
					from 
						provas_solicitadas ps
						inner join provas_presenciais pp on (ps.id_prova_presencial = pp.id_prova_presencial and pp.ativo = 'S')
					where
						pp.data_realizacao = '".$data."' and
						idmatricula in(".implode(',',$this->idsMatriculas).") and 
						ps.ativo = 'S'";
		$this->ordem_campo = 'pp.data_realizacao asc, pp.id_prova_presencial'; 
		$this->ordem = 'desc'; 
		$this->limite = -1; 
		
		return $this->retornarLinhas();
	}
	
	function gerarCalendarioAluno($idioma, $mes, $ano, $idsindicato = false) {
		$calendario = '
		<div class="calendar">
			<div class="days">
				<table>
					<thead>
						<tr class="week">
							<td>'.$idioma['domingo'].'</td>
							<td>'.$idioma['segunda'].'</td>
							<td>'.$idioma['terca'].'</td>
							<td>'.$idioma['quarta'].'</td>
							<td>'.$idioma['quinta'].'</td>
							<td>'.$idioma['sexta'].'</td>
							<td>'.$idioma['sabado'].'</td>
						</tr>
					</thead>
					<tbody>';
		
		$totalDias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano); //DIAS DO MES
		$primeiroDia = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mes, 1, $ano), 0); //PRIMEIRO DIA DO MES, SENDO 0 DOMINGO E 6 SABADO
		$ultimoDia = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mes, $totalDias, $ano), 0); //ULTIMO DIA DO MES, SENDO 0 DOMINGO E 6 SABADO
		
		//SE O PRIMEIRO DIA NÃO FOR NO DOMINGO CRIA LINHA COM DIAS DO MES ANTERIOR
		if($primeiroDia != 0) {
	
			$mesAnterior = $mes - 1;
			$anoMesAnterior = $ano;
			if($mesAnterior == 0) { $mesAnterior = 12; $anoMesAnterior--; } //PEGANDO ANO ANTERIOR SE MES ANTERIOR FOR MENOR QUE 1
	
			$totalDiasAnterior = cal_days_in_month(CAL_GREGORIAN, $mesAnterior, $anoMesAnterior);
			$ultimoDiaAnterior = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mesAnterior, $totalDiasAnterior, $anoMesAnterior), 0);
			$qtdDiasAnterior = ($totalDiasAnterior - $ultimoDiaAnterior);
	
			$calendario .= '<tr class="numbers">';
			$count = 0;
			for ($i = $qtdDiasAnterior; $i <= $totalDiasAnterior; $i++) {
				$count++;
				$calendario .= '<td class="days-opac">'.$i.'</td>';
			}
		} else {
			$calendario .= '<tr class="numbers">';
		}

		//if($this->proximo) $this->dia = 1;
		for ($i = 1; $i <= $totalDias; $i++) {
			$diaSemana = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mes, $i, $ano), 0); //DIA DA SEMANA
			
			$dia = $i;
			if($dia < 10) $dia = '0'.$dia;
			$data = $ano.$mes.$dia;
			
			$calendario .= '<td';
			
			$totalChatsProvas = $this->retornarTotalChatsProvasNaData($data);
			if($totalChatsProvas['total_chats'] || $totalChatsProvas['total_provas']) { 
				$title = '';
				if($totalChatsProvas['total_chats']) {
					$title .= $totalChatsProvas['total_chats'].' '.$idioma['chats'];
					if($totalChatsProvas['total_provas']) $title .= ' | '.$totalChatsProvas['total_provas'].' '.$idioma['provas'];
				} else
					if($totalChatsProvas['total_provas']) $title .= $totalChatsProvas['total_provas'].' '.$idioma['provas'];
				 
				$calendario .= ' class="alert-day conteudo" data-link="/'.$this->url[0].'/'.$this->url[1].'/'.$this->url[2].'/'.$data.'"><a rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="'.$title.'">'.$i.'</a></td>';  
			} else
				$calendario .= '>'.$i.'</td>';
	
			if($diaSemana == '6') $calendario .= '</tr>'; //SE SABADO FECHA A LINHA
			if($diaSemana == '6' && $i <= $totalDias){ //SE SABADO E EXITIR PROXIMA LINHA CRIA NOVA LINHA
				$calendario .= '<tr>';				
			}
		}

		//SE ULTMO DIA NAO FOR SABADO ENTAO IMPRIME DIAS DO MES POSTERIOR
		if($ultimoDia != 6){
			$qtdDiasPosterior =  6 - $ultimoDia; //DIAS QUE FALTAM NO CALENDARIO
	
			for ($i = 1; $i <= $qtdDiasPosterior; $i++) {
				$calendario .= '<td class="days-opac">'.$i.'</td>';
			}
	
			$calendario .= '</tr>'; //FECHA A LINHA
		}
	
		$calendario .= '</tbody></table></div></div>';
		
		return $calendario;
	}
	
	function retornarChatsProvasNaData($data) {
		$retorno['chats'] = array();
		if(count($this->idsAvas) > 0) {
			$this->sql = "select 
								ac.*,
								a.nome as ava
							from 
								avas_chats ac
								inner join avas a on (ac.idava = a.idava)
							where
								(date_format(ac.inicio_entrada_aluno,'%Y%m%d') <= '".$data."' or ac.inicio_entrada_aluno is null) and
								(date_format(ac.fim_entrada_aluno,'%Y%m%d') >= '".$data."' or ac.fim_entrada_aluno is null) and
								ac.ativo = 'S' and 
								a.idava in (".implode(',',$this->idsAvas).")";
			
			$this->ordem_campo = 'ac.inicio_entrada_aluno asc, ac.idchat'; 
			$this->ordem = 'desc'; 
			$this->limite = -1; 
			
			$retorno['chats'] = $this->retornarLinhas();
		}
		
		$retorno['provas'] = array();
		if(count($this->idsMatriculas) > 0) {
			$this->sql = "select 
							pp.*,
							ps.id_solicitacao_prova,
							ps.idmatricula,
							ps.situacao,
							c.nome as curso,
							p.nome_fantasia as escola,
							mcsp.nome as motivo,
							mcsp.descricao						
						from 
							provas_solicitadas ps
							inner join provas_presenciais pp on (ps.id_prova_presencial = pp.id_prova_presencial and pp.ativo = 'S')
							inner join matriculas m on (ps.idmatricula = m.idmatricula)
							left outer join cursos c on (m.idcurso = c.idcurso)
							left outer join escolas p on (ps.idescola = p.idescola)
							left outer join motivos_cancelamento_solicitacao_prova mcsp on (ps.idmotivo = mcsp.idmotivo)
						where
							date_format(pp.data_realizacao,'%Y%m%d') = '".$data."' and
							ps.idmatricula in(".implode(',',$this->idsMatriculas).") and 
							ps.ativo = 'S'";
			$this->ordem_campo = 'pp.data_realizacao asc, pp.id_prova_presencial'; 
			$this->ordem = 'desc'; 
			$this->limite = -1; 
			
			$retorno['provas'] = $this->retornarLinhas();
		}
		
		return $retorno;
	}
	
	function retornarTotalChatsProvasNaData($data) {
		$retorno['total_chats'] = 0;
		if(count($this->idsAvas) > 0) {
			$this->sql = "select 
								count(*) as total
							from 
								avas_chats ac
								inner join avas a on (ac.idava = a.idava)
							where
								(date_format(ac.inicio_entrada_aluno,'%Y%m%d') <= '".$data."' or ac.inicio_entrada_aluno is null) and
								(date_format(ac.fim_entrada_aluno,'%Y%m%d') >= '".$data."' or ac.fim_entrada_aluno is null) and
								ac.ativo = 'S' and 
								a.idava in (".implode(',',$this->idsAvas).")";
			
			$totalChats = $this->retornarLinha($this->sql);
			$retorno['total_chats'] = $totalChats['total'];
			
		}
		
		$retorno['total_provas'] = 0;
		if(count($this->idsMatriculas) > 0) {
			$this->sql = "select 
							count(*) as total
						from 
							provas_solicitadas ps
							inner join provas_presenciais pp on (ps.id_prova_presencial = pp.id_prova_presencial and pp.ativo = 'S')
						where
							date_format(pp.data_realizacao,'%Y%m%d') = '".$data."' and
							ps.idmatricula in(".implode(',',$this->idsMatriculas).") and 
							ps.ativo = 'S'";
			
			$totalProvas = $this->retornarLinha($this->sql);
			$retorno['total_provas'] = $totalProvas['total'];
		}
		
		return $retorno;
	}
	
	function retornarDisiciplinasProvas($id_solicitacao_prova) {
		$retorno = array();
		
		$this->sql = "select 
							d.nome
						from 
							provas_solicitadas_disciplinas psd
							inner join disciplinas d on (psd.iddisciplina = d.iddisciplina and psd.ativo = 'S')
						where
							psd.id_solicitacao_prova = ".$id_solicitacao_prova;
		
		$this->ordem_campo = 'd.nome'; 
		$this->ordem = 'desc'; 
		$this->limite = -1; 
		
		$disciplinas = $this->retornarLinhas();
		foreach($disciplinas as $disciplina) {  
			$retorno[] = $disciplina['nome'];
		}
		
		return $retorno;
	}
	
}

?>