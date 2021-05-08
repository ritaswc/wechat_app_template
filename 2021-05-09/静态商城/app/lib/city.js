

function init(that){
    let area = require('laraArea.js');
    let province = ['-请选择-'];
    let provinceIdx = [0];
    let city = {};
    let cityIdx = [];
    let district = {};
    let districtIdx =[];
    let provinceIndex = 0;
    let cityIndex = 0;
    let districtIndex = 0;
    let selectedCity = '-请选择-';
    let selectedProvince = '-请选择-';
    let selectedDistrct = '-请选择-';
    let cityFlag = 0;
    let disFlag = 0;
    console.log(area);
    area.forEach((item,key) => {
        provinceIdx.push(item.id);
        province.push(item.name);
        const sort = (provinceIdx.length) - 1;
        cityIdx[sort] = [];
        const cityArr = [];
        if(that.data.province != 0 && that.data.province == item.id) {
            provinceIndex = key + 1;
            selectedProvince = item.name;
        }
        item.child.forEach( (sItem, sKey) => {
            cityIdx[sort].push(sItem.id);
            cityArr.push(sItem.name);
            const districtArr = [];
 
            districtIdx[sItem.id] =[];
            sItem.child.forEach((tItem, tkey) => {
                districtIdx[sItem.id].push(tItem.id);
                districtArr.push(tItem.name);
                if(that.data.county != 0 && that.data.county == tItem.id) {
                    districtIndex = tkey;
                    selectedDistrct = tItem.name;
                }
            })
           
            district[sItem.name] = districtArr;
            //  if(disFlag) {
            //     selectedDistrct = district[sItem.name];
            //     disFlag = 0;
            // }
            if(that.data.city != 0 && that.data.city == sItem.id) {
                cityIndex = sKey;
                selectedCity = sItem.name
            }
        })
        city[item.name] = cityArr;
        // if(cityFlag) {
        //     selectedCity =  '沈阳市';
        //     cityFlag = 0;
        // }
    }); 
    const addressList={
    province:province,
    city:city,
    district:district,
    provinceIdx: provinceIdx,
    cityIdx:cityIdx,
    districtIdx:districtIdx,
    provinceIndex: provinceIndex,
    cityIndex: cityIndex,
    districtIndex: districtIndex,
    selectedProvince:selectedProvince,
    selectedCity:selectedCity,
    selectedDistrct:selectedDistrct
    };
    that.setData( { 
        'addressSelect': addressList 
    }); 
    console.log(that.data);
    var bindProvinceChange = function(e){
        var city=that.data.addressSelect;
        that.setData({
            'addressSelect.provinceIndex': e.detail.value,
            'addressSelect.selectedProvince':
                city.province[e.detail.value],
            'addressSelect.selectedCity':
                city.city[city.province[e.detail.value]][0],
            'addressSelect.selectedDistrct':
                city.district[city.city[city.province[e.detail.value]][0]][0],
            'addressSelect.cityIndex':0,
            'addressSelect.districtIndex':0
        });
    };
    var bindCityChange = function(e){
        var city=that.data.addressSelect;
        that.setData({
            'addressSelect.cityIndex': 
                e.detail.value,
            'addressSelect.selectedCity':
                city.city[city.selectedProvince][e.detail.value],
            'addressSelect.districtIndex':0,
            'addressSelect.selectedDistrct':
                city.district[city.city[city.selectedProvince][e.detail.value]][0]
        });
    };
    var bindDistrictChange = function(e){
        var city=that.data.addressSelect;
        that.setData({
            'addressSelect.districtIndex': e.detail.value,
            'addressSelect.selectedDistrct':city.district[city.selectedCity][e.detail.value]
        });
        console.log(this);
    };
    that['bindProvinceChange']=bindProvinceChange;
    that['bindCityChange'] = bindCityChange;
    that['bindDistrictChange'] = bindDistrictChange;
}

module.exports={
    init:init
}