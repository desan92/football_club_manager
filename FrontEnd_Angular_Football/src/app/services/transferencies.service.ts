import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class TransferenciesService {

  constructor(private http: HttpClient) { }

  get token(): string
  {
    return localStorage.getItem('x-token') || '';
  }

  transferenciesClub(id: number, page: number){

    return this.http.get(`http://localhost:8000/api/allTransferenciesJugadorclub/${id}?page=${page}`, {
      headers: {
        "Content-Type":  "application/json",
        Accept: "application/json",
        Authorization: 'Bearer ' + this.token
      }
    });
    
  }

  transferenciesEntrenadorClub(id: number, page: number){

    return this.http.get(`http://localhost:8000/api/allTransferenciesEntrenadorclub/${id}?page=${page}`, {
      headers: {
        "Content-Type":  "application/json",
        Accept: "application/json",
        Authorization: 'Bearer ' + this.token
      }
    });
    
  }

  ventaJugador(idclub: number, idjugador: number){

    const data = {'club_from_id' : idclub.toString(), 'jugador_id': idjugador.toString()};
    const headers = {
      "Content-Type":  "application/json",
      Accept: "application/json",
      Authorization: 'Bearer ' + this.token
    };

    return this.http.post(`http://localhost:8000/api/ventaJugador`, data, {
      headers
    });
    
  }

  compraJugador(idclubFrom: number, idjugador: number, idclubto: number){

    let data;

    if(!idclubFrom){
      data = { 'jugador_id': idjugador.toString(), 'club_to_id': idclubto.toString() };
    }
    else{
      data = {'club_from_id' : idclubFrom.toString(), 'jugador_id': idjugador.toString(), 'club_to_id': idclubto.toString() };
    }

    
    const headers = {
      "Content-Type":  "application/json",
      Accept: "application/json",
      Authorization: 'Bearer ' + this.token
    };

    return this.http.post(`http://localhost:8000/api/compraJugador`, data, {
      headers
    });
    
  }

  ventaEntrenador(idclub: number, idjugador: number){

    const data = {'club_from_id' : idclub.toString(), 'entrenador_id': idjugador.toString()};
    const headers = {
      "Content-Type":  "application/json",
      Accept: "application/json",
      Authorization: 'Bearer ' + this.token
    };

    return this.http.post(`http://localhost:8000/api/ventaEntrenador`, data, {
      headers
    });
    
  }

  compraEntrenador(idclubFrom: number, identrenador: number, idclubto: number){

    let data;

    if(!idclubFrom){
      data = { 'entrenador_id': identrenador.toString(), 'club_to_id': idclubto.toString() };
    }
    else{
      data = {'club_from_id' : idclubFrom.toString(), 'entrenador_id': identrenador.toString(), 'club_to_id': idclubto.toString() };
    }

    
    const headers = {
      "Content-Type":  "application/json",
      Accept: "application/json",
      Authorization: 'Bearer ' + this.token
    };

    return this.http.post(`http://localhost:8000/api/compraEntrenador`, data, {
      headers
    });
    
  }

}
