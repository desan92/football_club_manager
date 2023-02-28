import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DadesPartitComponent } from './dades-partit.component';

describe('DadesPartitComponent', () => {
  let component: DadesPartitComponent;
  let fixture: ComponentFixture<DadesPartitComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DadesPartitComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(DadesPartitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
