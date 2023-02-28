import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class PartitsService {

  constructor(private http: HttpClient) { }

  get token(): string
  {
    return localStorage.getItem('x-token') || '';
  }


  crearPartit(club_local_id: number, club_visitant_id: number){

    const body = {'club_local_id' : club_local_id.toString(), 'club_visitant_id': club_visitant_id.toString()}
    const headers =  {
      //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
      //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
      "Content-Type":  "application/json",
      Accept: "application/json",
      Authorization: 'Bearer ' + this.token
    }

    return this.http.post('http://localhost:8000/api/creategame',body, {headers});

  }

  allGamesSquad(id:number){
    return this.http.get(`http://localhost:8000/api/allgameClub/${id}`,{
      headers: {
        //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
        //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
        "Content-Type":  "application/json",
        Accept: "application/json",
        Authorization: 'Bearer ' + this.token
      }
    });
  }

  crearDadesPartit(partit_id: number){

    const body = {'partit_id' : partit_id.toString()};
    const headers =  {
      //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
      //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
      "Content-Type":  "application/json",
      Accept: "application/json",
      Authorization: 'Bearer ' + this.token
    }

    return this.http.post('http://localhost:8000/api/creategameStats',body, {headers});

  }

  allGamesStats(id:number){
    return this.http.get(`http://localhost:8000/api/gameStats/${id}`,{
      headers: {
        //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
        //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
        "Content-Type":  "application/json",
        Accept: "application/json",
        Authorization: 'Bearer ' + this.token
      }
    });
  }

}
