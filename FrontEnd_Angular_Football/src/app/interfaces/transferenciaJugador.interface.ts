import { Club } from './club.interface';
import { Jugador } from './jugador.interface';

export interface TransferJugador{
    id: number,
    club_from_id?: Club, 
    club_to_id?: Club, 
    jugador_id: Jugador,
    created_contract: string,
    contract_min: string,
}