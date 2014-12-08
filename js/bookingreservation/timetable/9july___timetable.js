


var Timetable = Class.create()

//------------------------------------------------------------------------------
// Constants
//------------------------------------------------------------------------------

Timetable.VERSION = '1.1'



//------------------------------------------------------------------------------
// Timetable Setup
//------------------------------------------------------------------------------

Timetable.setup = function(params)
{

  function param_default(name, def) {
    if (!params[name]) params[name] = def
  }

  param_default('timeSlot', null)
  
  param_default('startBusinessHrs', null)
  param_default('startBusinessMin', null)
  param_default('startBusinessDaypart',  null)
  param_default('srartBusinessFixMinutes',  null)
  
  param_default('selectedCalendarDay',  null)
  param_default('reservedTimeOfTheDay',  null)  
  
  param_default('endBusinessHrs', null)
  param_default('endBusinessMin', null)
  param_default('endBusinessDaypart',  null)
  param_default('endBusinessFixMinutes',  null)
  
  
  if(params.srartBusinessFixMinutes == null){
      params.srartBusinessFixMinutes = 0;
  }
  
  if(params.endBusinessFixMinutes == null){
      params.endBusinessFixMinutes = 60;
  }
  
  var total_slots = Timetable.calculateTotalSlots(params);
  
  Timetable.updateTable(params);
  
}



Timetable.calculateTotalSlots = function(params){
  
  
  var total_minutes_difference = Math.abs(params.startBusinessMin - params.endBusinessMin);
  var booking_time_slot = params.timeSlot;
  
  var total_slots = total_minutes_difference/booking_time_slot;                             
  
  
  return parseInt(total_slots);
  
}


Timetable.chekIsSlotBusyOrFree = function(params, cell, c_hr, c_min, c_dayp){
  
  var busySlots = params.reservedTimeOfTheDay;
  if(c_dayp == 'pm' && c_hr!=12 ){        
          c_hr = c_hr+12;          
  }
  
  for(var i=0; i<busySlots.length; i++){
    
      
      
      var f_hr = parseNumber(busySlots[i]['reserve_from_time'].substring(0, 2));
      var f_min = parseNumber(busySlots[i]['reserve_from_time'].substring(3, 5));
      var f_dayp = busySlots[i]['reserve_from_time'].substring(6, 8);
      if(f_dayp == 'pm' && f_hr!=12 ){        
          f_hr = f_hr+12;          
      }
      
      var t_hr = parseNumber(busySlots[i]['reserve_to_time'].substring(0, 2));
      var t_min = parseNumber(busySlots[i]['reserve_to_time'].substring(3, 5));
      var t_dayp = busySlots[i]['reserve_to_time'].substring(6, 8);
      //round the to minutes - to match the slot
      var t_remainder = t_min%parseNumber(params.timeSlot);
      var t_ans = t_min/parseNumber(params.timeSlot);
      
      if(t_remainder>0){
          t_min = (t_ans+1)*parseNumber(params.timeSlot);
      }
      
      
      if(t_dayp == 'pm' && t_hr!=12 ){        
          t_hr = t_hr+12;          
      }
      
      
      
      
      //check whether cell lies bw this time
      for(k=f_hr; k<=t_hr; k++){
        
         
          if(c_hr == k){ // that hr is reserve by its minutes  
            
            if(k==f_hr){
                if(c_min>f_min){  // that cell is reserve 
                   
                    cell.setAttribute('class','busy_time')
                    
                }
            }
            
            if(k>f_hr && k<t_hr){
                   
                    cell.setAttribute('class','busy_time')
                
            }
            
            if(k==t_hr){
                if(c_min<=t_min){  // that cell is reserve 
                   
                    cell.setAttribute('class','busy_time')
                    
                }
            }
            
            
            if(k==f_hr && k==t_hr){
                if(c_min>f_min && c_min<=t_min){  // that cell is reserve 
                   
                    cell.setAttribute('class','busy_time')
                    
                }else{
                    
                    cell.setAttribute('class','free_time')
                }
            }
          }
        
        
      }
      
      
      
  }
  
  //alert(busySlots);  
  
}



Timetable.updateTable = function(params){
  
  //Time slots with on one hour(60 min)
    var hour_slots = parseInt(60/params.timeSlot);
    
    
  // Timetable
    var table = new Element('table')
  
  // Timetable Header
    var thead = new Element('thead')
    
    table.appendChild(thead)
    table.addClassName('fme_booking_timetable')
    
    // Title Placeholder
    var row  = new Element('tr')
    
    
     
    if(params.startBusinessDaypart == 'pm' && params.endBusinessDaypart == 'pm'){
      
      if(parseNumber(params.startBusinessHrs) == 12){
        var start_hour_of_day = 0;
      }else{
        var start_hour_of_day = parseNumber(params.startBusinessHrs);
      }
      
      for (var i = start_hour_of_day; i < parseNumber(params.endBusinessHrs) ; ++i) {
        cell = new Element('td',{ colSpan: hour_slots }).update(i+'<br> PM');
        if(i == start_hour_of_day && start_hour_of_day==0){
          cell = new Element('td',{ colSpan: hour_slots }).update(12+'<br> PM');
        }       
        row.appendChild(cell)
      }
      
    }
    
    if(params.startBusinessDaypart == 'am' && params.endBusinessDaypart == 'am'){
      
      if(parseNumber(params.startBusinessHrs) == 12){
        var start_hour_of_day = 0;
      }else{
        var start_hour_of_day = parseNumber(params.startBusinessHrs);
      }
      
      for (var i = start_hour_of_day; i < parseNumber(params.endBusinessHrs) ; ++i) {
        cell = new Element('td',{ colSpan: hour_slots }).update(i+'<br> AM');
        if(i == start_hour_of_day && start_hour_of_day==0){
          cell = new Element('td',{ colSpan: hour_slots }).update(12+'<br> PM');
        }
        row.appendChild(cell)
      }
      
    }
    
    
    if(params.startBusinessDaypart != params.endBusinessDaypart){
      
      for (var i = parseNumber(params.startBusinessHrs); i < 12 ; ++i) {
        cell = new Element('td',{ colSpan: hour_slots }).update(i+'<br> AM')
        row.appendChild(cell)
      }
      
    }
    
     
     
      
      
      
      if(params.startBusinessDaypart != params.endBusinessDaypart){
        
        var end_hour_of_time = parseNumber(params.endBusinessHrs);
        if(params.endBusinessFixMinutes == 60){
            end_hour_of_time = parseNumber(params.endBusinessHrs)-1;
        }
    
        cell = new Element('td',{ colSpan: hour_slots }).update(12+'<br> PM')
        row.appendChild(cell)
      
        for (var i = 1; i<= end_hour_of_time; ++i) {
          cell = new Element('td',{ colSpan: hour_slots }).update(i+'<br> PM')
          row.appendChild(cell)
        }
      }
      
      
      
      
    
    
      thead.appendChild(row)
  
    
      
    // Timetable Body
      var tbody = new Element('tbody')
      
      table.appendChild(tbody)
      table.addClassName('fme_booking_timetable')
    
      var brow  = new Element('tr')
      
      var total_slots = Timetable.calculateTotalSlots(params);
      
      
      
      if(params.startBusinessDaypart != params.endBusinessDaypart){
        
        
                  
                  // for morning 
                  for (var i = parseNumber(params.startBusinessHrs); i <= 12 ; ++i) {
                    
                      
                      var this_hr_slot_min = 0;
                      var all_hr_slot_min = 0;
                      
                      for(var j=1; j<=hour_slots; j++){
                        
                              cell = new Element('td')
                              
                              
                              //start of business minutes with in first hour
                              if(i==parseNumber(params.startBusinessHrs))
                              {              
                                this_hr_slot_min = j*params.timeSlot;
                                
                                if(parseNumber(this_hr_slot_min) <= params.srartBusinessFixMinutes){
                                  // make cell white
                                }else{
                                  cell.setAttribute('class','free_time')
                                  var hr = i;
                                  var min = parseNumber(this_hr_slot_min);
                                  var dayp = params.startBusinessDaypart;                      
                                  Timetable.chekIsSlotBusyOrFree(params, cell, hr, min, dayp);
                                  
                                  //cell.setAttribute('class','free_time')     
                                }
                                
                              }else{
                                cell.setAttribute('class','free_time')
                                all_hr_slot_min = j*params.timeSlot;
                                
                                var hr = i;
                                var min = parseNumber(all_hr_slot_min);
                                var dayp = params.startBusinessDaypart;                      
                                Timetable.chekIsSlotBusyOrFree(params, cell, hr, min, dayp);
                                  
                                //cell.setAttribute('class','free_time')
                              }
                              
                              all_hr_slot_min = j*params.timeSlot;
                              if(all_hr_slot_min==60)
                                if(i==12)
                                cell.setAttribute('title',1+':'+'00');
                                else
                                cell.setAttribute('title',i+1+':'+'00');
                              else
                                cell.setAttribute('title',i+':'+all_hr_slot_min);
                              
                              brow.appendChild(cell)
                        
                      }
                  
                  }
      
      
      
      
      
      
      
      
                    var last_hour_of_time = parseNumber(params.endBusinessHrs);
                    if(params.endBusinessFixMinutes == 60){
                        last_hour_of_time = parseNumber(params.endBusinessHrs)-1;
                    }
                    
                    
                    // for evening 
                    for (var i = 1; i<= last_hour_of_time; ++i) {
                        
                        var this_hr_slot_min = 0;
                         var all_hr_slot_min = 0;
                         
                        for(var j=1; j<=hour_slots; j++){
                          
                          cell = new Element('td')
                          
                          //end of business minutes with in lasr hour
                          if(i==parseNumber(params.endBusinessHrs))
                          {              
                            this_hr_slot_min = j*params.timeSlot;
                            
                            if(parseNumber(this_hr_slot_min) > params.endBusinessFixMinutes){
                              // make cell white
                            }else{
                              
                                cell.setAttribute('class','free_time')
                                var hr = i;
                                var min = parseNumber(this_hr_slot_min);
                                var dayp = params.endBusinessDaypart;                      
                                Timetable.chekIsSlotBusyOrFree(params, cell, hr, min, dayp);
                              
                            }
                            
                          }else{
                            
                            cell.setAttribute('class','free_time')
                            all_hr_slot_min = j*params.timeSlot;
                                  
                            var hr = i;
                            var min = parseNumber(all_hr_slot_min);
                            var dayp = params.endBusinessDaypart;                      
                            Timetable.chekIsSlotBusyOrFree(params, cell, hr, min, dayp);
                            
                            //cell.setAttribute('class','free_time')
                          }
                          
                          all_hr_slot_min = j*params.timeSlot;
                          if(all_hr_slot_min==60)
                            cell.setAttribute('title',i+1+':'+'00');
                          else
                            cell.setAttribute('title',i+':'+all_hr_slot_min);
                            
                          brow.appendChild(cell)
                          
                        }
                    }
      
    }
      
      
      
    //IF BOTH START AND END DAYPART ARE EQUEAL
    
    if(params.startBusinessDaypart == params.endBusinessDaypart){
        
                    var last_hour_of_time = parseNumber(params.endBusinessHrs);
                    if(params.endBusinessFixMinutes == 60){
                        last_hour_of_time = parseNumber(params.endBusinessHrs)-1;
                    }
                    
                    if(parseNumber(params.startBusinessHrs) == 12){
                      var start_hour_of_day = 0;
                    }else{
                      var start_hour_of_day = parseNumber(params.startBusinessHrs);
                    }
                  
                  // for morning, evening both 
                  for (var i = start_hour_of_day; i <= last_hour_of_time; i++) {
                    
                      
                      var this_hr_slot_min = 0;
                      var all_hr_slot_min = 0;
                      
                      for(var j=1; j<=hour_slots; j++){
                        
                              cell = new Element('td')
                              
                              
                              //start of business minutes with in first hour
                              if(i==start_hour_of_day)
                              {              
                                this_hr_slot_min = j*params.timeSlot;
                                
                                if(parseNumber(this_hr_slot_min) <= params.srartBusinessFixMinutes){
                                  // make cell white
                                }else{
                                  cell.setAttribute('class','free_time')
                                  var hr = i;
                                  var min = parseNumber(this_hr_slot_min);
                                  var dayp = params.startBusinessDaypart;                      
                                  Timetable.chekIsSlotBusyOrFree(params, cell, hr, min, dayp);
                                  
                                  //cell.setAttribute('class','free_time')     
                                }
                                
                              }else{
                                cell.setAttribute('class','free_time')
                                all_hr_slot_min = j*params.timeSlot;
                                
                                var hr = i;
                                var min = parseNumber(all_hr_slot_min);
                                var dayp = params.startBusinessDaypart;                      
                                Timetable.chekIsSlotBusyOrFree(params, cell, hr, min, dayp);
                                  
                                //cell.setAttribute('class','free_time')
                              }
                              
                              all_hr_slot_min = j*params.timeSlot;
                              if(all_hr_slot_min==60){
                                
                                if(i==12)
                                  cell.setAttribute('title',1+':'+'00');
                                else
                                  cell.setAttribute('title',i+1+':'+'00');
                              
                              }else{
                              
                                cell.setAttribute('title',i+':'+all_hr_slot_min);
                                if(i == start_hour_of_day && start_hour_of_day==0){
                                  cell.setAttribute('title',12+':'+all_hr_slot_min);
                                }
                              }
                              
                              
                                
                              brow.appendChild(cell)
                        
                      }
                  
                  }
      
      
                    
      
    }
      
      
      
    
    tbody.appendChild(brow);
    
    $('fme_booking_timetable').update(table);   
    
}

