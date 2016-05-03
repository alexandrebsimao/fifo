<?php

class Processa
{
	public $_inicioCpu;
	public $_inicioParada;
	public $_terminoParada;
	public $_qtdProcessos;
	public $_intChegada;
	public $_tempoServico;
	public $_listaProcessos;

	public function __construct()
	{
		$this->_inicioCpu 		= $_POST['inicioCpu'];

		$this->_inicioParada 	= $_POST['inicioParada'];
		$this->_terminoParada 	= $_POST['terminoParada'];

		$this->_qtdProcessos 	= $_POST['qtdProcessos'];
		$this->_intChegada 		= $_POST['intChegada'];
		$this->_tempoServico 	= $_POST['tempoServico'];
	}

	/*
	 * Método para inicio do processo
	 */
	public function criarProcessosPorGrupo(){

		$tempoUltimoAtendimento = 0;
		$ultimoIntervaloChegada = 0;

		$listaFila = array();


		foreach ($this->_qtdProcessos as $chaveGrupo => $qtdProcesso) {
			$tempoUltimoAtendimento = (isset($listaFila[$chaveGrupo-1]) && count($listaFila[$chaveGrupo-1])) > 0 ? $listaFila[$chaveGrupo-1][count($listaFila[$chaveGrupo-1])-1]['tempoFinalAtendimento'] : 0;
			$ultimoIntervaloChegada = (isset($listaFila[$chaveGrupo-1]) && count($listaFila[$chaveGrupo-1])) > 0 ? $listaFila[$chaveGrupo-1][count($listaFila[$chaveGrupo-1])-1]['instanciaChegada'] : 0;

			$processos = array(
				'grupoProcesso' 	=> $chaveGrupo,
				'qtdProcessos' 		=> $qtdProcesso,
				'intervaloChegada'	=> $this->_intChegada[$chaveGrupo],
				'numTempoServico'	=> $this->_tempoServico[$chaveGrupo]
				);

			$listaFila[$chaveGrupo] = $this->GetListaProcessosGrupo($processos, $tempoUltimoAtendimento, $ultimoIntervaloChegada);
		}

		$this->_listaProcessos = $listaFila;

		$this->gerarArquivo();

		return $listaFila;

	}

	/*
	 * Obtém a lista dos processos por grupo
	 */
	private function GetListaProcessosGrupo($processos, $tempoUltimoAtendimento, $ultimoIntervaloChegada){

		$somaIntervalo 		= $ultimoIntervaloChegada;
		$ultimoAtendimento 	= 0;
		$ultimoAtendimento 	= $tempoUltimoAtendimento;

		for($p=0; $p < $processos['qtdProcessos']; $p++){

			$somaIntervalo = $processos['intervaloChegada'];

			$instanciaAtendimento = $this->GetInstanciaAtendimento($somaIntervalo, $processos['numTempoServico'], $ultimoAtendimento);

			$tempoFinalAtendimento = $this->GetTempoFinalAtendimento($processos['numTempoServico'], $instanciaAtendimento);

			$fila[] = array(
				'grupoProcesso' 		=> $processos['grupoProcesso'],
				'processo'				=> "P" + $p,
				'instanciaChegada' 		=> $somaIntervalo,
				'tempoServico' 			=> $processos['numTempoServico'],
				'instanciaAtendimento' 	=> $instanciaAtendimento,
				'tempoPermanenciaFila' 	=> ($instanciaAtendimento - $somaIntervalo),
				'tempoFinalAtendimento' => $tempoFinalAtendimento,
				);

			$ultimoAtendimento = $tempoFinalAtendimento;
		}

		return $fila;

	}

	/*
	 * Obtém as instancias de atendimento dos processos
	 */
	private function GetInstanciaAtendimento($instanciaChegada, $tempoServico, $finalUltimoAtendimento){
		$primeiro 				= !(bool) $finalUltimoAtendimento;
		$tempoInicio 			= $this->_inicioCpu;
		$tempoEspera 			= 0;
		$atendimento 			= 0;

		if($finalUltimoAtendimento < 0)
			$finalUltimoAtendimento = $finalUltimoAtendimento*-1;

		$tempoEspera = $primeiro ? ($tempoInicio < (int) $instanciaChegada) ? (int) $instanciaChegada : $tempoInicio : $finalUltimoAtendimento;

		$parada = $this->GetParadas1($finalUltimoAtendimento);

		if($parada != null){
			$tempoEspera = $parada;
		}

		$atendimento += $tempoEspera;

		return $atendimento;
	}

	/*
	 * Obtém o tempo final de atendimento
	 */
	private function GetTempoFinalAtendimento($tempoServico, $instanciaAtendimento){
		$somaTempoInstancia 	= $tempoServico + $instanciaAtendimento;
		$tempoFinalAtendimento 	= 0; 
		$tempoExecutadoParada 	= 0;

		$parada = $this->GetParadas2($somaTempoInstancia, $instanciaAtendimento);

		if($parada != null){
			$tempoFinalAtendimento = $this->VerificaTempoFinal($parada, $instanciaAtendimento, $tempoServico , $tempoExecutadoParada);
		}else{
			$tempoFinalAtendimento = $instanciaAtendimento + $tempoServico;
		}

		return $tempoFinalAtendimento;
	}

	/*
	 * Obtém verifica o tempo final
	 */
	private function VerificaTempoFinal($paradas, $instanciaAtendimento, $tempoServico, $tempoExecutadoParada){
		$tempoFinalAtendimento = 0;

		$tempoFinalAtendimento = $instanciaAtendimento + $tempoServico + ($paradas['fim'] - $paradas['inicio']);

		$paradas = $this->GetParadas3($paradas['fim'], $tempoFinalAtendimento);

		if($paradas != null){
			$tempoExecutadoParada += $paradas['inicio'] - $paradas['fim'];
        	return $this->VerificaTempoFinal($paradas, $instanciaAtendimento, $tempoServico, $tempoExecutadoParada);
		}

		return $tempoFinalAtendimento;
	}

	/**
	 * Os metódos abaixo são para listagem e verificações das paradas
	 */
	private function GetParadas1($finalUltimoAtendimento){
		foreach ($this->_inicioParada as $chave => $inicio) {
			if($finalUltimoAtendimento < $this->_terminoParada[$chave] && $finalUltimoAtendimento >= $inicio){
				return $this->_terminoParada[$chave];
			}
		}

		return null;
	}

	private function GetParadas2($somaTempoInstancia, $instanciaAtendimento){
		foreach ($this->_inicioParada as $chave => $inicio) {

			if(($instanciaAtendimento > $this->_inicioParada[$chave] && $somaTempoInstancia < $this->_inicioParada[$chave]) || ($instanciaAtendimento < $this->_terminoParada[$chave] && $somaTempoInstancia > $this->_terminoParada[$chave])){
				return array(
					'inicio' 	=> $inicio,
					'fim'		=> $this->_terminoParada[$chave]
					);
			}
		}

		return null;
	}

	private function GetParadas3($paradaFim, $tempoFinalAtendimento){
		foreach ($this->_inicioParada as $chave => $inicio) {

			if(($tempoFinalAtendimento <= $this->_terminoParada[$chave] && $tempoFinalAtendimento >= $inicio)){
				return array(
					'inicio' 	=> $inicio,
					'fim'		=> $this->_terminoParada[$chave]
					);
			}
		}

		return null;
	}

	/*
	 * Método para gerar arquivo
	 */
	function gerarArquivo(){
		$file = fopen('arquivos/'.date('YmdHis').'.csv', 'a');

		$escrita = "Grupo; Processo; Inst. Chegada; Tempo de Serviço; Inst. Atendimento; Tempo Perm. Fila \r\n";

		foreach ($this->_listaProcessos as $grupo => $processos) {
			$escrita .= "Grupo " . ($grupo+1) . " \r\n";
			foreach ($processos as $processo) {
				$escrita .= ($processo['grupoProcesso']+1) . "; ".($processo['processo']+1)."; ".$processo['instanciaChegada']."; ".$processo['tempoServico']."; ".$processo['instanciaAtendimento']."; ".$processo['tempoPermanenciaFila']." \r\n";
			}
		}

		fwrite($file, $escrita);
		fclose($file);
	}
}