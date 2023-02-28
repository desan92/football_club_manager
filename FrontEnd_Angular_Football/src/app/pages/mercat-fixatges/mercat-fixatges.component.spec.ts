import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MercatFixatgesComponent } from './mercat-fixatges.component';

describe('MercatFixatgesComponent', () => {
  let component: MercatFixatgesComponent;
  let fixture: ComponentFixture<MercatFixatgesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MercatFixatgesComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(MercatFixatgesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
