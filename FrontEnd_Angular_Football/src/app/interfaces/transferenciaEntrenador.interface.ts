import { Club } from './club.interface';
import { Entrenador } from './entrenador.interface';

export interface TransferEntrenador{
    id: number,
    club_from_id?: Club, 
    club_to_id?: Club, 
    entrenador_id: Entrenador,
    created_contract: string,
    contract_min: string,
}