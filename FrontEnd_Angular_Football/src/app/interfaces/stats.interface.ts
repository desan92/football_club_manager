import { Jugador } from './jugador.interface';
export interface Stats{
    id: number,
    esdeveniment: string,
    jugador_id: number,
    club_id: number,
    partit_id: number,
    created_at: string,
    updated_at: string,
    jugador: Jugador
}