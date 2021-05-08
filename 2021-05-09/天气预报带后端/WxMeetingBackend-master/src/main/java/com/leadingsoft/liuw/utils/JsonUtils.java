package com.leadingsoft.liuw.utils;

import com.fasterxml.jackson.annotation.JsonAutoDetect;
import com.fasterxml.jackson.annotation.JsonInclude;
import com.fasterxml.jackson.core.JsonParseException;
import com.fasterxml.jackson.core.type.TypeReference;
import com.fasterxml.jackson.databind.*;
import com.fasterxml.jackson.databind.ser.PropertyFilter;
import com.fasterxml.jackson.databind.ser.impl.SimpleBeanPropertyFilter;
import com.fasterxml.jackson.databind.ser.impl.SimpleFilterProvider;
import com.leadingsoft.liuw.exception.CustomRuntimeException;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.util.StringUtils;

import java.io.IOException;
import java.io.Reader;
import java.text.SimpleDateFormat;
import java.util.List;
import java.util.Map;

/**
 * Created by liuw on 2017/4/21.
 */
public class JsonUtils {
    private static final Logger logger = LoggerFactory.getLogger(JsonUtils.class);
    public static final TypeReference<Map<String, Object>> MAP_TYPE = new TypeReference() {
    };
    private static ObjectMapper mapper = new ObjectMapper();
    private static ObjectMapper filterMapper;

    private JsonUtils() {
    }

    public static <T> String pojoToJson(T pojo) {
        if(pojo == null) {
            return null;
        } else {
            try {
                String e = getMapper().writeValueAsString(pojo);
                return e;
            } catch (IOException var2) {
                throw new CustomRuntimeException("Failed to convert Object2JSONString. ", new Object[]{var2});
            }
        }
    }

    public static <T> T jsonToPojo(String json, Class<T> pojoClass) {
        if(!StringUtils.hasText(json)) {
            return null;
        } else {
            try {
                return getMapper().readValue(json, pojoClass);
            } catch (IOException var3) {
                throw new CustomRuntimeException("Failed to convert JSONString2Object. ", new Object[]{var3});
            }
        }
    }

    public static <T> List<T> jsonToPojoList(String json, TypeReference<List<T>> valueTypeRef) {
        if(!StringUtils.hasText(json)) {
            return null;
        } else {
            try {
                return (List)getMapper().readValue(json, valueTypeRef);
            } catch (IOException var3) {
                throw new CustomRuntimeException("Failed to convert JSONString2Object. ", new Object[]{var3});
            }
        }
    }

    public static <T> T jsonToPojo(Reader src, Class<T> pojoClass) throws JsonParseException, JsonMappingException, IOException {
        return getMapper().readValue(src, pojoClass);
    }

    public static <T> T jsonToPojo(String json, TypeReference<T> valueTypeRef) {
        if(!StringUtils.hasText(json)) {
            return null;
        } else {
            try {
                return getMapper().readValue(json, valueTypeRef);
            } catch (IOException var3) {
                throw new CustomRuntimeException("Failed to convert JSONString2Object. ", new Object[]{var3});
            }
        }
    }

    public static Map<String, Object> jsonToMap(String json) {
        if(!StringUtils.hasText(json)) {
            return null;
        } else {
            try {
                return (Map)getMapper().readValue(json, MAP_TYPE);
            } catch (IOException var2) {
                throw new CustomRuntimeException("Failed to convert JSONString2Map. ", new Object[]{var2});
            }
        }
    }

    public static <T> T pojoToPojo(T sourceObject, Class<T> targetType) {
        return sourceObject == null?null:getMapper().convertValue(sourceObject, targetType);
    }

    public static <T> Map<String, Object> pojoToMap(T pojo) {
        return pojo == null?null:(Map)getMapper().convertValue(pojo, MAP_TYPE);
    }

    public static <T> Map<String, Object> pojoToMapWithSpecifiedFields(T pojo, String... fields) {
        String json = pojoToJsonWithSpecifiedFields(pojo, fields);
        return jsonToMap(json);
    }

    public static <T> Map<String, Object> pojoToMapWithFilterFields(T pojo, String... fields) {
        String json = pojoToJsonWithFilterFields(pojo, fields);
        return jsonToMap(json);
    }

    public static <T> Map<String, Object> pojoToMapWithSpecifiedFields(T pojo, String filterId, String... fields) {
        String json = pojoToJsonWithSpecifiedFields(pojo, filterId, fields);
        return jsonToMap(json);
    }

    public static <T> Map<String, Object> pojoToMapWithFilterFields(T pojo, String filterId, String... fields) {
        String json = pojoToJsonWithFilterFields(pojo, filterId, fields);
        return jsonToMap(json);
    }

    public static <T> String pojoToJsonWithSpecifiedFields(T pojo, String... fields) {
        return pojo == null?null:pojoToJsonWithSpecifiedFields(pojo, pojo.getClass().getName(), fields);
    }

    public static <T> String pojoToJsonWithFilterFields(T pojo, String... fields) {
        return pojo == null?null:pojoToJsonWithFilterFields(pojo, pojo.getClass().getName(), fields);
    }

    public static <T> String pojoToJsonWithSpecifiedFields(T pojo, String filterName, String... fields) {
        if(pojo == null) {
            return null;
        } else {
            if(StringUtils.isEmpty(filterName)) {
                filterName = pojo.getClass().getName();
            }

            return pojoToJson(pojo, filterName, SimpleBeanPropertyFilter.filterOutAllExcept(fields));
        }
    }

    public static <T> String pojoToJsonWithFilterFields(T pojo, String filterName, String... fields) {
        if(pojo == null) {
            return null;
        } else {
            String filter = filterName;
            if(StringUtils.isEmpty(filterName)) {
                filter = pojo.getClass().getName();
            }

            return pojoToJson(pojo, filter, SimpleBeanPropertyFilter.serializeAllExcept(fields));
        }
    }

    public static <T> Map<String, Object> pojoToMap(T pojo, PropertyFilter filter) {
        if(pojo == null) {
            return null;
        } else {
            String json = pojoToJson(pojo, pojo.getClass().getName(), filter);
            return jsonToMap(json);
        }
    }

    public static <T> Map<String, Object> pojoToMap(T pojo, String filterName, PropertyFilter filter) {
        if(pojo == null) {
            return null;
        } else {
            String json = pojoToJson(pojo, filterName, filter);
            return jsonToMap(json);
        }
    }

    public static <T> String pojoToJson(T pojo, PropertyFilter filter) {
        return pojo == null?null:pojoToJson(pojo, pojo.getClass().getName(), filter);
    }

    public static <T> String pojoToJson(T pojo, String filterName, PropertyFilter filter) {
        if(StringUtils.isEmpty(filterName)) {
            filterName = pojo.getClass().getName();
        }

        SimpleFilterProvider filters = (new SimpleFilterProvider()).addFilter(filterName, filter);

        try {
            String e = getFilterMapper().writer(filters).writeValueAsString(pojo);
            if(logger.isDebugEnabled()) {
                logger.debug("pojoToJson :" + e);
            }

            return e;
        } catch (IOException var5) {
            throw new CustomRuntimeException("Failed to convert Object2JSONString. ", new Object[]{var5});
        }
    }

    private static ObjectMapper getMapper() {
        if(mapper != null) {
            return mapper;
        } else {
            Class var0 = JsonUtils.class;
            synchronized(JsonUtils.class) {
                if(mapper != null) {
                    return mapper;
                } else {
                    mapper = new ObjectMapper();
                    mapper.setDateFormat(new SimpleDateFormat("yyyy-MM-dd HH:mm:ss"));
                    return mapper;
                }
            }
        }
    }

    private static ObjectMapper getFilterMapper() {
        if(filterMapper != null) {
            return filterMapper;
        } else {
            Class var0 = JsonUtils.class;
            synchronized(JsonUtils.class) {
                if(filterMapper != null) {
                    return filterMapper;
                } else {
                    filterMapper = new ObjectMapper();
                    filterMapper.setDateFormat(new SimpleDateFormat("yyyy-MM-dd HH:mm:ss"));
                    return filterMapper;
                }
            }
        }
    }

    static {
        mapper.setSerializationInclusion(JsonInclude.Include.ALWAYS);
        mapper.setVisibility(mapper.getSerializationConfig().getDefaultVisibilityChecker().withFieldVisibility(JsonAutoDetect.Visibility.ANY).withGetterVisibility(JsonAutoDetect.Visibility.NONE).withSetterVisibility(JsonAutoDetect.Visibility.NONE).withCreatorVisibility(JsonAutoDetect.Visibility.NONE));
        mapper.configure(DeserializationFeature.FAIL_ON_UNKNOWN_PROPERTIES, false);
        mapper.configure(SerializationFeature.WRITE_BIGDECIMAL_AS_PLAIN, true);
        mapper.configure(MapperFeature.SORT_PROPERTIES_ALPHABETICALLY, true);
    }
}
